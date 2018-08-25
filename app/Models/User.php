<?php

namespace App\Models;

use Cuatromedios\Kusikusi\Models\Entity;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Hash;
use Cuatromedios\Kusikusi\Models\EntityData;
use Cuatromedios\Kusikusi\Models\Authtoken;

class User extends EntityData implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    const PROFILE_ADMIN = 'admin';
    const PROFILE_EDITOR = 'editor';
    const PROFILE_USER = 'user';

    public static $dataFields = ['name', 'email', 'password', 'profile'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public static function authenticate($email, $password, $ip) {
        $user = User::where('email', $email)->first();
        if($user && Hash::check($password, $user->password)){
            $apikey = base64_encode(str_random(40));
            $token = new Authtoken([
                'token' => $apikey,
                'created_ip' => $ip
            ]);
            // TODO: If the user is inactive or deleted don't allow login
            $user->authtokens()->save($token);
            $entity = Entity::getOne($user->id, ['e.id', 'e.model', 'e.isActive', 'd.name', 'd.email', 'd.profile']);
            $relations = Entity::getEntityRelations($user->entity_id, NULL, ['e.id', 'r.kind', 'r.position', 'r.tags', 'e.model']);
            $entity['relations'] = $relations;
            return ([
                "token" => $apikey,
                "entity" => $entity
            ]);
        } else {
            return FALSE;
        }
    }

    /**
     * Get the authokens of the User.
     */
    public function authtokens()
    {
        return $this->hasMany('Cuatromedios\Kusikusi\Models\Authtoken', 'user_id');
    }

    /**
     * Get the permissions of the User
     */
    public function permissions()
    {
        return $this->hasMany('Cuatromedios\Kusikusi\Models\Permission', 'user_id');
    }

    /**
     * Get the activity related to the User.
     */
    public function activity()
    {
        return $this->hasMany('Cuatromedios\Kusikusi\Models\Activity', 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        self::saving(function($user) {
            if (isset($user['password'])) {
                $user['password'] = Hash::make($user['password']);
            }
        });
    }
}
