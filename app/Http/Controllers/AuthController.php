<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phonenumber' => 'required|string|min:11',
            'address' => 'required|string',
        ]);

        //$validation = $request->validate();
        if ($validation->fails()) {
            return response()->json(['status' => "error", 'message'  => 'User registeration failed', "errors" => $validation->errors()]);
        }

        $user = User::create([
            'name' => $request->firstname,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phonenumber' => $request->phonenumber,
            'address' => $request->address,
            // 'account_number' => $request->account_number,	
            // 'bvn' => $request->bvn,
            // 'card_number' => $request->card_number,
        ]);
        $status = $user->save();

        if ($status) {
            return response()->json(array('status' => "success", 'message' => 'User has been successfully register.', 'data' => $user));
        } else {
            return response()->json(['status' => "error", 'message'  => 'User registeration failed']);
        }
    }

    public function login(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => "error", 'message'  => 'User registeration failed', "data" => $validation->errors()]);
        }

        $credentials =   request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message'  => 'Invalid credentials', 401]);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks();
        $token->save();

        return response()->json(array(
            'data' => array(
                'user' => Auth::user(),
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expired_at' => Carbon::parse($token->expires_at)->toDateTimeString()


            )
        ));
    }
}
