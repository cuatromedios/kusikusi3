<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Data\User;
use App\Models\ApiResponse;
use App\Exceptions\ExceptionDetails;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of all entities.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request) {
        try {
            $authResult = User::authenticate($request->input('email'), $request->input('password'), $request->ip());
            if ($authResult !== FALSE) {
                return (new ApiResponse($authResult, TRUE))->response();
            } else {
                $status = 401;
                return (new ApiResponse(NULL, FALSE, 'Email or password incorrect', $status))->response();
            }

        } catch (\Exception $e) {
            $status = 500;
            return (new ApiResponse(NULL, FALSE, ExceptionDetails::filter($e), $status))->response();
        }
        // TODO: Validate input
        /* $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]); */
    }

}
