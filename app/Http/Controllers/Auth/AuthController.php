<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegister;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use HttpResponses;
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function register(StoreUserRequest $request)
    {

        $request->validated($request->all());

        $user = User::create(array(
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ));
        return response()->json([
            'token' => $user->createToken('API TOKEN of ' . $user->name)->plainTextToken,
        ], 201);
    }

    public function logIn(LoginUserRequest $request)
    {
        $request->validated($request->all());
        if (!Auth::attempt($request->only(['email', 'password']))) {

            return response()->json([
                'message' => 'Creantials do not match',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return  response()->json([
            'message'   => 'User Logged In Successfuly',
            'token'     => $user->createToken('API TOKEN of ' . $user->name)->plainTextToken,
        ], 200);
    }
    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();
        return  \response()->json([
            'message' => 'logout',
        ], 200);
    }
}
