<?php

namespace ppeCore\dvtinh\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use ppeCore\dvtinh\Database\Factories\UserFactory;

class User extends Authenticatable
{
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    protected $connection = 'ppe_core';
    use HasFactory, Notifiable;
    use HasApiTokens;

    protected static function newFactory()
    {
        return UserFactory::new();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'avatar',
        'banner',
        'first_name',
        'platform',
        'access_token_social',
        'social_id',
        'username',
        'phone_number',
        'quotes',
        'avatar_attachment_id',
        'background_attachment_id',
        'avatar_attachment',
        'background_attachment',
        'roles',
        'permission',
        'role_label',
        'is_flag',
        'country',
        'date_of_birth',
        'gender',
        'address',
        'current_address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'json',
        'permission' => 'json',
        'avatar_attachment' => 'json',
        'background_attachment' => 'json',
        'current_address' => 'json',
    ];
    protected  $attributes = [
        'avatar' => 'https://i.imgur.com/jfZDmVD.png',
        'banner' => 'https://i.imgur.com/TGrWoue.jpg'
    ];

    public function assignRole($role)
    {
        $type = gettype($role);
        switch ($type) {
            case "array":
                $roles = Role::whereIn("id", $role)->get();
                foreach ($roles as $role) {
                    $data = [
                        "user_id" => $this->id,
                        "role_id" => $role->id
                    ];
                    UserHasRole::updateOrCreate($data, $data);
                }
                return $this;
            default:
                if ((int)$role) {
                    $role = Role::find($role);
                } else {
                    $role = Role::where("name", $role)->first();
                }
                $data = [
                    "user_id" => $this->id,
                    "role_id" => $role->id
                ];
                UserHasRole::updateOrCreate($data, $data);
                return $this;
        }
    }
    public function role($role){
//        return UserHasRole::SelectRaw("u.*")
//                ->leftJoin("users as u", "u.id", "=", "user_has_roles.user_id")
//                ->leftJoin("roles as r", "r.id", "=", "user_has_roles.role_id")
//                ->where("r.name", $role);
        $userHasRole = UserHasRole::selectRaw("user_has_roles.*")
            ->leftJoin("roles as r", "r.id", "=", "user_has_roles.role_id")
            ->where("r.name", $role)
            ->get()
            ->pluck('user_id');
        return User::whereIn("id", @$userHasRole ??[]);
    }

    public function hasPermissionTo($permission)
    {
        $role = RoleHasPermission::SelectRaw("r.*")
                ->leftJoin("permissions as p", "p.id", "=", "role_has_permissions.permission_id")
                ->leftJoin("roles as r", "r.id", "=", "role_has_permissions.role_id")
                ->where("p.name", $permission)->first();
        $userHasRole = UserHasRole::where("user_id", $this->id)
                                    ->where("role_id", @$role->id)
                                    ->first();
        if(isset($userHasRole)){
            return true;
        }
        return false;
    }

    public function syncRoles($role){
        $role = Role::create(["name" => $role]);
        return $this->assignRole($role->name);
    }

    public function removeRole($role){
        $type = gettype($role);
        switch ($type) {
            case "array":
                $userHasRole = UserHasRole::where("user_id", $this->id)
                                            ->whereIn("role_id", $role);
                break;
            default:
                if ((int)$role) {
                    $role = Role::find($role);
                } else {
                    $role = Role::where("name", $role)->first();
                }
                $userHasRole = UserHasRole::where("user_id", $this->id)
                                ->where("role_id", $role->id);
        }
        $userHasRole->delete();
        return $this;
    }

   public function getRoles(){
       $userHasRole = UserHasRole::where("user_id", $this->id)
           ->get()
           ->pluck('role_id');
       return Role::whereIn("id", @$userHasRole ?? [])->get();
   }
}
