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
        $validation = Validator::make($request->all(), [
            'account_number' => 'required|string|max:11',
            'bvn' => 'required|string|max:11',
            'card_number' => 'required|string',
            'cvv' => 'required|int|max:3',
            'bank_name' => 'required|string',
            'bank_code' => 'required|string',
            'card_pin' => 'required|string|max:4',
            'nin' => 'required|string|max:11',
        ]);

        if ($validation->fails()) {
            return response()->json(['status' => "error", 'message'  => 'Details update failed', "data" => $validation->errors()]);
            exit();
        }

        $validate_status = $this->validateBvn($request->bvn);

        if (!$validate_status) {
            return response()->json(['status' => "error", 'message'  => 'Bvn is inccorect, enter a valid bvn', "data" => $validation]);
        }

        $update_response = User::whereId($request->user()->id)->update([
            'bvn' => $request->bvn,
            'card_number' => $request->card_number,
            'cvv' => $request->card,
            'bank_name' => $request->bank_name,
            'bank_code' => $request->bank_code,
            'card_pin' => $request->card_pin,
            'nin' => $request->nin,
        ]);

        if ($update_response) {
            return response()->json(['status' => "success", 'message'  => "Details successfully updated"]);
        }
    }

    public function validateBvn($Bvn)
    {
        $curl = curl_init();
        $sandbox_key = env('SAND_BOX_KEY');

        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://fsi.ng/api/v1/flutterwave/v3/kyc/bvns/$Bvn?bvn=$Bvn",
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 40,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => [
                "Accept: */*",
                "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36",
                "Content-Type: application/json",
                "Connection: keep-alive",
                "sandbox-key: $sandbox_key",
                "Authorization: dskjdks"


            ],
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return $err;
        } else {
            if (isset($response)) {
                $response_details =  json_decode($response);
                if ($response_details->status == "success") {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
