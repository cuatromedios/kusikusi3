<?php

namespace App\Providers;

use App\Models\Data\User;
use App\Models\Entity;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Authtoken;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Gate::define('get-entity', function ($user, $entity_id) {
            foreach ($user->permissions as $permission) {
                if ($permission->get && Entity::isSelfOrDescendant($entity_id, $permission->entity_id)) {
                   return true;
                }
            }
            return false;
        });
        Gate::define('post-entity', function ($user, $entity_id) {
            foreach ($user->permissions as $permission) {
                if ($permission->post && Entity::isSelfOrDescendant($entity_id, $permission->entity_id)) {
                    return true;
                }
            }
            return false;
        });
        Gate::define('patch-entity', function ($user, $entity_id) {
            foreach ($user->permissions as $permission) {
                if ($permission->patch && Entity::isSelfOrDescendant($entity_id, $permission->entity_id)) {
                    return true;
                }
            }
            return false;
        });
        Gate::define('delete-entity', function ($user, $entity_id) {
            foreach ($user->permissions as $permission) {
                if ($permission->delete && Entity::isSelfOrDescendant($entity_id, $permission->entity_id)) {
                    return true;
                }
            }
            return false;
        });

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header(Authtoken::AUTHORIZATION_HEADER)) {
                $key = explode(' ',$request->header(Authtoken::AUTHORIZATION_HEADER))[1];
                // TODO: Also check the ip of the request, stored in the tokens table
                $user = User::whereHas('authtokens', function ($query) use ($key) {
                    $query->where('token', '=', $key);
                })->first();
                if(!empty($user)){
                    $request->request->add(['user_id' => $user->entity_id]);
                    $request->request->add(['user_profile' => $user->profile]);
                }
                return $user;
            } else {
                return NULL;
            }
        });
    }
}
