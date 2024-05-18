<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(User $user)
    {
        // model as dependency injection
        $this->user = $user;
    }

    public function register(Request $request)
    {
        // validate the incoming request
        // set every field as required
        // set email field so it only accept the valid email format

        $this->validate($request, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'eid' => 'required|string|min:2|max:255',
            'password' => 'required|string|min:6|max:255',
        ]);

        // if the request valid, create user

        $user = $this->user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'eid' => $request['eid'],
            'password' => bcrypt($request['password']),
        ]);

        // login the user immediately and generate the token
        $token = auth()->login($user);

        // return the response as json 
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User created successfully!',
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,    // get token expires in seconds
                ],
            ],
        ]);
    }

    public function login(Request $request)
    {
        // return $request;
        // 'eid' => 'required|string',
        $this->validate($request, [
            'eid' => 'required|string',
            'password' => 'required|string',
        ]);


        // HR User data bypass
        $md5 = md5($request->password);
        $user = User::where('eid', $request->eid)->first();
        // $user = User::where('oldpass',$md5)->first();
        
        if(!empty($user) && $user->oldpass == $md5){
            
            // $newpass = Hash::make($request->password);
            $newpass = bcrypt($request['password']);
            $data = User::where('id', $user->id)->first();
            
            $data->password = $newpass;
            // $data->is_check = 1;
            $data->update();
            // return $data;
        }

        // attempt a login (validate the credentials provided)
        // 'eid' => $request->eid,
        $token = auth()->attempt([
            'eid' => $request->eid,
            'password' => $request->password,
        ]);

        // if token successfully generated then display success response
        // if attempt failed then "unauthenticated" will be returned automatically
        if ($token)
        {
            return Response(['access_token' => $token, "token_type" => "Bearer", "status" => true, "message" => "Login has been Successfully", 'user' => auth()->user(), 'expires_in' => auth()->factory()->getTTL() * 60], 200);
            // return response()->json([
            //     'meta' => [
            //         'code' => 200,
            //         'status' => 'success',
            //         'message' => 'Quote fetched successfully.',
            //     ],
            //     'data' => [
            //         'user' => auth()->user(),
            //         'access_token' => [
            //             'token' => $token,
            //             'type' => 'Bearer',
            //             'expires_in' => auth()->factory()->getTTL() * 60,
            //         ],
            //     ],
            // ]);
        }
    }

    public function logout()
    {
        // get token
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);

        if($invalidate) {
            return Response(['data' => 'User Logout Successfully Done!!!'],200);
            // return response()->json([
            //     'meta' => [
            //         'code' => 200,
            //         'status' => 'success',
            //         'message' => 'Successfully logged out',
            //     ],
            //     'data' => [],
            // ]);
        }
    }
}