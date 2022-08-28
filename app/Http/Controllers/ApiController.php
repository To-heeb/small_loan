<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function getAllBanks()
    {
        $curl = curl_init();
        $sandbox_key = env('SAND_BOX_KEY');

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://fsi.ng/api/v1/flutterwave/v3/banks/NG?country=NG',
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
                return response($response);
            }
        }
    }
}
