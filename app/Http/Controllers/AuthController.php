<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register','logout']]);
    }

    public function register(RegisterRequest $request){
        try{

                DB::beginTransaction();
                $user=User::create([
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password),
                ]);
               
                $token = Auth::login($user);

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'User created successfully',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);
       

        } catch(\Throwable  $th){

            DB::rollback();
            Log::error($th);
            return response()->json([
                'status'=>'user not created',
                'error'=>$th->getMessage(),
                
            ], 500);

    }
  
    }


    public function login(LoginRequest $request){


        try{
            
            DB::beginTransaction();
            
            $credentials = $request->only('email', 'password');
            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
    
            $user = Auth::guard('api')->user();

            DB::commit();
            return response()->json([
                    'status' => 'success',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ]
                ]);

        
    
    }catch(\Throwable  $th){

        DB::rollback();
        Log::error($th);
        return response()->json([
            'status'=>'user not authorized',
            'error'=>$th->getMessage(),
            
        ], 500);

}
        
    }
    
    public function logout(){
        Auth::guard('api')->logout();

    return response()->json([
        'status' => 'success',
        'message' => 'logout'
    ], 200);
    }
}
