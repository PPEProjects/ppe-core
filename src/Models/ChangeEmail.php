<?php

namespace ppeCore\dvtinh\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeEmail extends Model
{
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    protected $connection = 'ppe_core';
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "old_email",
        "new_email",
        "user_id"
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
}
