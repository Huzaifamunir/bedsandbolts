<?php
   
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Order; 
use App\Models\Cart; 
use App\Models\Transation; 
use Illuminate\Http\Request;
use Session;
use Stripe;

   
class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        return view('stripe');
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {   
       
    
       
        $check=User::where('email',$request->stripeemail)->first();
        
          if($check==null)
          {
          $user=new User;
          $user->name=$request->stripename;
          $user->email=$request->stripeemail;
          $user->password='$2y$10$vMAHISQUrYGJiap5bF2b5exg3kMEms/iGxuUbO5TUlEZr4sgGVQ32';
          $user->usertype='1';
          $user->save();
          $user_id=$user->id;
        }else
        {
            $user_id=$check->id;
        }
        
          $order=Order::create([
              'user_id'=>$user_id,
              'name'=>$request->stripename,
                    'contact'=>$request->stripephone,
                    'country'=>$request->stripecountry,
                    'address'=>$request->stripeaddress,
                    'city'=>$request->stripecity,
                    'zipcode'=>$request->stripezip,
                    'products'=>$request->striprecart,
                    'email'=>$request->stripeemail,
                    'note'=>$request->stripemessage,
                    'status'=>'1',
                    'payment_method'=>'Payment with card',
                    'amount' => $request->total_amount,
                    'order_date'=>date('Y-m-d'),
                    

          ]);
          $transation= Transation::create([
            'user_id'=>$user_id,
            'order_id'=>$order->id,
            'name'=>$request->stripename, 
            'email'=>$request->stripeemail,
            'amount' => $request->total_amount,
            'account'=>$request->account_no, 
            'card_type'=>$request->stripecardbrand,
          ]);
           \Mail::to($request->stripeemail)->send(new\App \Mail\Invoice($order));
          session()->put('order_complete',$order);
          $deletecartitem=Cart::where('user_id',session('dummy_session_id'))->delete();
          Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
          Stripe\Charge::create ([  
                  "amount" => $request->total_amount,
                 "currency" => "usd",
                 "source" => $request->stripeToken,
                 "description" => "Test payment from itsolutionstuff.com." 
          ]);
 
  
          
          
       return back()->with('success','order placed');
    }
}