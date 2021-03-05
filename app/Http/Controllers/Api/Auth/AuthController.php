<?php

namespace App\Http\Controllers\Api\Auth;

use http\Env\Response;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['login' , 'register']] );
    }



    public function register(Request $request){        //    Create User Function

        $registerData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($registerData->fails()){
            return response()->json($registerData->errors(), 400);
        }

        if ($request->hasFile('image')){
            $image = $request['image']->store('user-photo');
        }
        if ($request->has('role_id')){
            $role = $request['role_id'];
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'image' => $image,
            'role_id' => $role

        ]);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response()->json(['Message' => 'User Created Successfully' , 'user' => $user , 'Access_Token' => $accessToken]);
    }





    public function login(Request $request){          //    Login User Function

        $loginData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if ( !auth()->attempt($loginData) ){
            return response(['Message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return  response()->json(['Message' => 'User Logged in Successfully' , 'user' => auth()->user() , 'Access_Token' =>$accessToken ]);

    }



    public function profile(){      //  Showing Authenticated User

        $user  = Auth::user();

        return [ 'User' => $user->posts , 'Posts' => $user->posts];

    }




    public function store(Request $request){         //     Creating User By Admin Or Author

        $createData = $request->all();

        if ($request->hasFile('image')){
            $image = $request['image']->store('user-image');

            $createData['image'] = $image;
        }

        if ($request->has('role_id')){
            $createData['role_id'] = $request['role_id'];
        }

        $createData['password'] = bcrypt($request['password']);

        $user = User::create($createData);


        return response(['Message' => 'User Created By Admin Or Author' , 'User' => $user]);
    }



    public function logout(){          // Logging Out The User

        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }

        return response(['Message' => 'User Logged Out Successfully']);
    }

    protected function guard(){

        return Auth::guard();
    }

}
