<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;  // add the User model

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
// use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Dotenv\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function me() 
    {
        // use auth()->user() to get authenticated user data

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => auth()->user(),
            ],
        ]);
    }

    public function index()
    {
        // return "test";
        return User::all();
        $outs = DB::select("SELECT * FROM hr.users");
        return $outs[0]->name;
    }

    public function store(Request $request)
    {
        // return $request;
        $user = new User();
        $user->id = $request->id;//'1';
        $user->name = $request->name;//"sofen";
        $user->email = $request->email;//"sofen@gmail.com";
        $user->password = $request->password;//"123";
        $user->eid = $request->eid;//"M01418";
        $user->update();
        return $user;
    }

    public function show()
    {
        return $user = User::where('id', 1)->first();
        // $outs = DB::select("SELECT * FROM hr.users");
        // return $outs[0]->name;
    }

    public function update(Request $request, string $id)
    {
        $oldpas = md5($request->password);
        $newpass = Hash::make($request->password);
        $data = User::where('eid', $request->eid)->first();
        $data->password = "";
        $data->oldpass = $oldpas;
        $data->update();
        return $data;

        $newpass = Hash::make($request->password);
        $data = User::where('id', $id)->first();
        // return $user = Auth::user();
        // if($user->id == $newPass->id){
            // return $newPass->id;
            $data->password = $newpass;
            // $data->is_check = 1;
            $data->update();
            // $user->currentAccessToken()->delete();
        
            return Response(['data' => $data],200);
        // }
        // return Response(['data' => 'Wrong User access.'],401);
        
    }

    public function destroy(string $id)
    {
        //
    }

    public function userLogin(Request $request){

        $md5 = md5($request->password);
        $user = User::where('eid', $request->eid)->first();
        // $user = User::where('oldpass',$md5)->first();
        
        if(!empty($user) && $user->oldpass == $md5){
            
            $newpass = Hash::make($request->password);
            $data = User::where('id', $user->id)->first();
            
            $data->password = $newpass;
            // $data->is_check = 1;
            $data->update();
        }
        if (Auth::attempt($request->all())) {

            $user = Auth::user();

            $success =  $user->createToken('Employee-Purchase', ['*'], now()->addMinutes(60))->plainTextToken;
            // $success =  $user->createToken($user->eid.'-AuthToken')->plainTextToken;

            return Response(['access_token' => $success, "token_type" => "bearer", "status" => true, "message" => "Login has been Successfully", 'user' => $user], 200);
        }

        return Response(['message' => 'email or password wrong'],401);
    }

    public function userDetails(){
        
        if (Auth::check()) {

            $user = Auth::user();

            return Response(['data' => $user],200);
        }

        return Response(['data' => 'Unauthorized'],401);
    }

    public function userLogout(){

        $user = Auth::user();

        $user->currentAccessToken()->delete();
        
        return Response(['data' => 'User Logout successfully.'],200);

    }
}