<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Kavenegar;

class SmsController extends Controller
{
    public function send(){
        $api_key="44324E676F34596742766F703946777453682F4E2B75744136776237774B31756C6D574B37324E656757343D";
        $receptor=989119216558;
        $token="zaeim";
        $template="test2";
        $data = [
            'receptor' =>$receptor,
            'token' => $token,
            'template' => $template,
        ];
        $jsonData = json_encode($data);
        $url_path = "verify/lookup.json?receptor=$receptor&token=$token&template=$template";
        $url="https://api.kavenegar.com/v1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . "/" . $api_key . "/" . $url_path);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $res = json_decode($result);
        curl_close($ch);
        dd($res);


//        $data = [
//            'MerchantID' =>$amount,
//            'Amount' => $amount,
//            'CallbackURL' => $this->callbackUrl,
//            'Description' => $this->portalDescription
//        ];
//        $jsonData = json_encode($data);
//        $ch = curl_init('https://www.zarinpal.com/pg/rest/WebGate/PaymentRequest.json');
//        curl_setopt($ch, CURLOPT_USERAGENT, 'ZarinPal Rest Api v1');
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Content-Type: application/json',
//            'Content-Length: ' . strlen($jsonData)
//        ));
//        $result = curl_exec($ch);
//        $err = curl_error($ch);
//        $result = json_decode($result, true);
//        curl_close($ch);
    }
    public function test(){
        $us=User::find(1);
        $user2=User::where('id',1)->first();
        dd($user2->orders->where('status',1));
    }
}
