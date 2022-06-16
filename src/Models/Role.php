<?php

namespace ppeCore\dvtinh\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    protected $connection = 'ppe_core';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    protected  $attributes = [];


    public function givePermissionTo($permission){
        $type = gettype($permission);
        switch ($type){
            case "array":
                $permissions = Permission::whereIn("id", $permission)->get();
                foreach ($permissions as $permission){
                    $data = [
                        "role_id" =>  $this->id,
                        "permission_id" => $permission->id
                    ];
                     RoleHasPermission::updateOrCreate($data, $data);
                }
                return $this;
            default:
                if((int)$permission) {
                    $permission = Permission::find($permission);
                }
                else {
                    $permission = Permission::where("name", $permission)->first();
                }
                $data = [
                    "role_id" =>  $this->id,
                    "permission_id" => $permission->id
                ];
                 RoleHasPermission::updateOrCreate($data, $data);
                return $this;
        }
    }


    public function revokePermissionTo($permission)
    {
        $type = gettype($permission);
        switch ($type){
            case "array":
                $roleHasPermission = RoleHasPermission::where("role_id", $this->id)
                    ->whereIn("permission_id", $permission);
                break;
            default:
                if((int)$permission) {
                    $permission = Permission::find($permission);
                }
                else {
                    $permission = Permission::where("name", $permission)->first();
                }
                $roleHasPermission = RoleHasPermission::where("role_id", $this->id)
                    ->where("permission_id", $permission->id);
        }
        $roleHasPermission->delete();
        return $this;
    }

    public function getPermissions(){
        $roleHasPermission = RoleHasPermission::where("role_id", $this->id)
                                                ->get()
                                                ->pluck("permission_id");
        return Permission::whereIn("id", @$roleHasPermission ?? [])->get();
    }

}
