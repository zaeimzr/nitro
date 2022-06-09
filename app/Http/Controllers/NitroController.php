<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\True_;
use function PHPUnit\Framework\isEmpty;

class NitroController extends Controller
{
    public function sendOtp(Request $request)
    {
        if (!empty($request->mobile)) {
            $request->validate([
                'mobile' => ['required', 'integer', ],
                'countryCode' => ['required', 'integer', ],
            ]);

            $mobile=$request->mobile;
            $countryCode=$request->countryCode;
            $user= new User;
            $user->mobile=$mobile;
            $user->countryCode=$countryCode;
            $rand=rand(100000,999999);
            $user->otp=$rand;
            $user->save();
            if ($user->otp != 0){
                $message="کد اعتبار سنجی برا شمت ارسال گردید";
                $status="success";
            }else{
                $message="کد اعتبار سنجی ارسال نشد!";
                $status="failed";
            }
            $data = [
                'status' => $status,
                'message' =>$message,
                'code' => $rand,
                'mobile' => $mobile,
            ];
            $json_data=json_encode($data);
            return $json_data;
        }


    }

    public function login(Request $request){
        $mobile=$request->mobile;
        $token=$this->make_token(25);
        $user=User::where('mobile',$mobile);

        if ($user->get('otp')->get(0) != null && ($request->activationCode) == $user->get()->get(0)->otp){
            $status="succes";
            $message="عملیات با موفقیت انجام شد";

            $user->update([
                "uuid"=> $request->uuid,
                "AUTHORIZATION" => $token,
            ]);
            $user->get('otp');

            $data = [
                'status' => $status,
                'message' =>$message,
                'data'=>[
                    "userToken"=>$token,
                ],
            ];
            $json_data=json_encode($data);
            return $json_data;
        } else {
            $status="failed";
            $message="کد فعالسازی نامعتبر است";
            $data = [
                'status' => $status,
                'message' =>$message,
            ];
            $json_data=json_encode($data);
            return $json_data;
        }

    }

    public function logout(Request $request){
    $data = [
        'status' => "success",
        'message' =>"خروج کاربر از نرم افزار با موفقیت انجام شد",
    ];
    $json_data=json_encode($data);
    return $json_data;
}
    public function failed_logout(){
    $data = [
        'status' => "failed",
        'message' =>"کد دسترسی کاربر معتبر نمی باشد",
    ];
    $json_data=json_encode($data);
    return $json_data;
}

    public function category(){
        $categories=Category::get();
        $data = [
            'status' => "success",
            'message' =>"عملیات با موفقیت انجام شد",
            'data'=>[
                'categories'=> $categories,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;
    }
    public function index(Request $request){
        $cat_id=$request->get('cat_id');
        $products=Product::where('cat_id',$cat_id)->get();
        $data = [
            'status' => "success",
            'message' =>"عملیات با موفقیت انجام شد",
            'data'=>[
                'products'=> $products,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;
    }

    public function newOrder(Request $request){
        $header_auth= request()->header('Authorization');
        $user=User::where('AUTHORIZATION',$header_auth)->get();

        $order=new Order;
        $product_id=$request->get('productId');
        $product=Product::where('id',$product_id);
        $order->link=$request->link;
        $order->user_id=$user->get(0)->id;
        $order->title=$product->get()->get(0)->title;
        $order->description=$product->get()->get(0)->description;
        $order->category_type=$product->get()->get(0)->category_type;
        $order->price=($request->get('count'))*($product->get()->get(0)->rate_per_toman);
        $order->count=$request->get('count');
        $order->trackingNumber=$this->make_number(10);
        $order->save();
        $data = [
            'status' => "success",
            'message' =>"عملیات با موفقیت انجام شد",
            'data'=>[
                'order'=> $order,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;
    }
    public function orders(){
        $orders=Order::get();
        $data = [
            'status' => "success",
            'message' =>"عملیات با موفقیت انجام شد",
            'data'=>[
                'Orders'=> $orders,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;

    }

    public function dashboard(Request $request){
        $header_auth= request()->header('Authorization');
        $user=User::where('AUTHORIZATION',$header_auth)->get();
        $currentUser=[
            'id'=>$user->get(0)->id,
            'full name'=>$user->get(0)->fullName,
            'phone number'=>$user->get(0)->mobile,
            'balance'=>$user->get(0)->balance,
            'crated at'=>$user->get(0)->created_at,
        ];
        $user2=User::where('AUTHORIZATION',$header_auth)->first();
        sizeof($user2->orders);

        $product=Product::get();
        $categories=Category::get();
        $data = [
            'status' => "success",
            'message' =>"عملیات با موفقیت انجام شد",
            'data'=>[
                'user'=> $currentUser,
                'statics'=>[
                    'ordersAll'=>sizeof($user2->orders),
                    'ordersProcessing'=>sizeof($user2->orders->where('status',0)),
                    'ordersDone'=>sizeof($user2->orders->where('status',1)),
                    ],
                'products'=>$product,
                'categories'=>$categories,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;
//        return $user->get(0)->fullName;
    }

    public function newPayment(Request $request){

        if ($request->gateWay == 1){
            $url="zarinpal";
            $message="عملیات با موفقیت انجام شد";
            $status="success";

            $header_auth= request()->header('Authorization');
            $user=User::where('AUTHORIZATION',$header_auth)->get();
            $payment=new Payment;
            $payment->price=$request->price;
            $payment->trackingNumber=$this->make_number(9);
            $payment->user_id=$user->get(0)->id;
            $payment->save();
        }elseif ($request->gateWay == 2){
            $url="newqioo";
            $message="عملیات با موفقیت انجام شد";
            $status="success";

            $header_auth= request()->header('Authorization');
            $user=User::where('AUTHORIZATION',$header_auth)->get();
            $payment=new Payment;
            $payment->price=$request->price;
            $payment->trackingNumber=$this->make_number(9);
            $payment->user_id=$user->get(0)->id;
            $payment->save();

        }else{
            $url=null;
            $message="دروازه پرداخت نامعتبر می باشد.";
            $status="failed";
        }


        $data = [
            'status' => $status,
            'message' =>$message,
            'data'=>[
                'url'=> $url,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;
    }

    public function payment(){
        $payment=Payment::get();
        $data = [
            'status' => "success",
            'message' =>"عملیات با موفقیت انجام شد",
            'data'=>[
                'Payments'=> $payment,
            ],
        ];
        $json_data=json_encode($data);
        return $json_data;
    }

    public function make_token (int $n){
        $numbers="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $token="";
        $number_length=strlen($numbers);
        for ($i=1;$i<=$n;$i++){
            $rand=rand(1,$number_length);
            $text= substr($numbers,$rand-1,1);
            $token=$token.$text;
        }
        return $token;
    }

    public function make_number (int $n){
        $numbers="0123456789";
        $token="";
        $number_length=strlen($numbers);
        for ($i=1;$i<=$n;$i++){
            $rand=rand(1,$number_length);
            $text= substr($numbers,$rand-1,1);
            $token=$token.$text;
        }
        return $token;
    }
}




