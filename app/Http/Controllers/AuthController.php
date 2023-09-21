<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        //store path image on db and image store on server 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/users');
            $image->move($destinationPath, $name);
            $input['profile_url'] = $name;
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        return  response()->json(['succes' => $success, 'data' => $input]);
    }
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token =  $user->createToken('MyApp')->accessToken;
            // $success['name'] =  $user->name;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    //check get current user login
    public function me()
    {
        $user = Auth::User(); //get current logged in user
        return response()->json(['user' => $user], 200);
    }

    //when user logout remove token;
    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['message' => 'Successfully logout'], 200);
    }

    //allow user to can be upadate
    public function updateUser(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        //dd($user);
        if ($user != null) {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $data['profile_url'] = $name;
                $oldImage =  $user->profile_url; //chekc oldImage for delete old on path public server

            }
            $user->update($data);
            $destinationPath = public_path('/images');

            //if existsfile delete oldimage
            if (file_exists($destinationPath . '/' . $oldImage)) {
                unlink($destinationPath . '/' . $oldImage);
            }
            return response()->json(['user' => $user], 200);
        } else {
            return response()->json(['error' => 'unauthorised'], 401);
        }
    }
}
