<?php

namespace App\Models\Data;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Models\Data;
use App\Models\Authtoken;
use Illuminate\Support\Facades\Hash;

class User extends Data implements AuthenticatableContract, AuthorizableContract
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
        'entity_id', 'name', 'email', 'password', 'profile'
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
            $user->authtokens()->save($token);
            return ([
                "token" => $apikey,
                "entity_id" => $user->entity_id,
                "profile" => $user->profile,
                "name" => $user->name
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
        return $this->hasMany('App\Models\Authtoken', 'entity_id');
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
