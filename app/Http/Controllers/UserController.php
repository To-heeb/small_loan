<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function updateDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|string',
            'bvn' => 'required|string',
            'card_number' => 'required|string',
            'cvv' => 'required|string|max:3',
            'bank_name' => 'required|string',
            'bank_code' => 'required|string',
            'card_pin' => 'required|string',
            'nin' => 'required|string',
        ]);




        User::whereId()->update();
    }
}
