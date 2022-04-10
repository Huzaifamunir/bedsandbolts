<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Bedsize;
use App\Models\Fabric;
use App\Models\Beeding;
use App\Models\Addon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Mattresssize;
use App\Models\Matching_storage;
use App\Models\Transation;
use App\Models\SofaOptions;
use App\Models\StoolOptions;
use App\Models\Subscribe;
use App\Models\SaleMonth;
use App\Models\Review;
use Carbon;






class GeneralController extends Controller
{
    


     public function productpage($id){
        
         $reviews=Review::where('product_id',$id)->get();
         $product1=Product::where('id' ,$id)->first(); 
         
         $category=Category::all(); 
        
         $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
         $monthlysale=SaleMonth::first();
     
         $related_products=Product::where('categories_id',$product1->categories_id)->get();
   
     return view('productpage',compact('product1','cart_data','related_products','category','monthlysale','reviews'));
}
    public function addcart(Request $request){
       
       $find=Cart::where('user_id',session('dummy_session_id'))->where('p_id',$request->p_id)->first();
      
       if($find==null)
       {
        $add_to_cart = Cart::create([
            'user_id'=>session('dummy_session_id'),
            'p_id'=>$request->p_id,
            'p_orignal_price'=>$request->p_price,
            'p_price'=>$request->qty*$request->p_price,
            'qty'=>$request->qty,
            'options'=>json_encode($request->option),
            'sale'=>$request->sale,
           
        ]);
            
       }else
       {
           $updateqty=Cart::where('user_id',session('dummy_session_id'))->where('p_id',$request->p_id)->first();
           $totalqty=$updateqty->qty+$request->qty;
           $updateqty->p_price=$totalqty*$request->p_price;
           $updateqty->qty=$totalqty;
           $updateqty->save();
       }
       
        return back()->with('success','Added to cart');
    }
    public function home(){
        return view('admin.home');
    }
  
    public function category(){
        return view('admin.category');
    }
    public function index(){
       date_default_timezone_set('Asia/Karachi');
              if(empty(session('dummy_session_id'))){
                session()->put('dummy_session_id',time());
              }
      
        $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();

        $category=Category::all(); 
        $monthsale=SaleMonth::first();
        // if($monthsale!=null)
        // {
        // $month=Carbon\Carbon::parse(now())->format('F');
        // $year=Carbon\Carbon::parse(now())->format('Y');
        // $mytime = Carbon\Carbon::now();
        // $date=Carbon\Carbon::parse(now())->format('d');
        // $time=date('H:i:s');
        // $expiretime=$month.' '.$date.','.' '.$year.' '.$time;
        // if($expiretime<=$monthsale->sale_month)
        // {
        //     dd($expiretime);
        //     $delet=SaleMonth::where('id',$monthsale->id)->delete();
        // }else
        // {
            
        //     dd('less');
        // }
        // }
        
        $product=Product::all(); 
        return view('index',compact('category','product','cart_data','monthsale'));
    }
    public function category1(){
        $bed=Bedsize::all();
        $sofa=SofaOptions::all();
        $stool=StoolOptions::all();
        $category=Category::all(); 
        $fabrics=Fabric::all();
        $beedings=Beeding::all();
        $addons=Addon::all();
        $mattress=Mattresssize::all();
        $matching_storages=Matching_storage::all();
       return view('admin.product',compact('category','bed','fabrics','beedings','addons','matching_storages','mattress','sofa','stool'));
    }
    public function redirects(){
         $paid = Order::where('status', 1)->count();
           $unpaid = Order::where('status', 0)->count();
         $sum=  Product::sum('price');
         
        $product=Product::count();
        $order= Order::count();
        $category=Category::all(); 
       $usertype=Auth::user()->usertype;
       
       if($usertype=='1'){
           return view('admin.home',compact('order','product','paid','unpaid','sum'));
       }
       else{
           return view('index',compact('category'));
       }
    }
    public function deleteitem($id){
        
        $user_id = session('dummy_session_id');
        $deletecartitem=Cart::where('user_id',session('dummy_session_id'))->first();
    
        $deletecartitem->delete();
        return back()->with('delete','Deleted From Cart');
    }
    public function checkout(){
        $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
        $category=Category::all(); 
        if($cart_data==null)
        {
            return redirect('/');
        }else
        {
        return view('checkout',compact('cart_data','category'));
        }

      }

      public function placeorder(Request $request)
      {
          
          $array=array();
         foreach(json_decode($request->cart) as $c)
         {  
             if($c->sale=='1')
             {
                 
                 array_push($array,$c);
             }
         }
         
          $check=User::where('email',$request->Email)->first();
          if($check==null)
          {
          $user=new User;
          $user->name=$request->txtName;
          $user->email=$request->Email;
          $user->password='$2y$10$vMAHISQUrYGJiap5bF2b5exg3kMEms/iGxuUbO5TUlEZr4sgGVQ32';
          $user->usertype='0';
          $user->save();
          $user_id=$user->id;
        }else
        {
            $user_id=$check->id;
        }
            
          $order=Order::create([
              'user_id'=>$user_id,
              'name'=>$request->txtName,
                    'contact'=>$request->txtPhone,
                    'country'=>$request->country,
                    'address'=>$request->address,
                    'city'=>$request->city,
                    'zipcode'=>$request->zip,
                    'products'=>$request->cart,
                    'email'=>$request->Email,
                    'note'=>$request->txtMessage,
                    'payment_method'=>'Cash on delivery',
                    'status'=>'0',
                    'order_date'=>date('Y-m-d'),
                    'amount'=>$request->total,

          ]);
          $deletecartitem=Cart::where('user_id',session('dummy_session_id'))->delete();
           \Mail::to($request->Email)->send(new\App \Mail\Invoice($order));
          session()->put('order_complete',$order);
        //   dd(session()->get('order_complete'));
          return back()->with('success','order placed');
      }
      public function order(){
        
        $order =Order::orderBy('id', 'DESC')->get();
        
   
         return view('admin.order', compact('order'));
     }
      public function deleteorder($id){
          
        $delete= Order::find($id);
        
        $delete->delete();
        return redirect()->back();
    }
    public function vieworder($id){
            $invoices= Order::where('id',$id)->first();
            return view('admin.invoice',compact('invoices'));
    }
    public function transationdata($id){
        $transations= Transation::where('id',$id)->get();
        return  view('admin.transation',compact('transations'));
       
    }
    
    public function byoptions($id,$type)
    {
        $type_id=$id;
         $product=Product::all() ;
            $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
  
            return view('product',compact('product','cart_data','type','type_id'));
    }
    
    public function allproducts()
    {
          $product=Product::paginate(20); 

  $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
  $category=Category::all(); 
  $monthlysale=SaleMonth::first();
  
  return view('allproducts',compact('product','cart_data','category','monthlysale'));
    }
    
    public function productsearch(Request $request)
    {
        
        
        if($request->keyword != ''){
            if(isset($request->cat_id))
        {
            $search=Product::where('name','like','%'.$request->keyword.'%')->where('categories_id',$request->cat_id)->get();
        }else
        {
        $search=Product::where('name','like','%'.$request->keyword.'%')->get();
        }
         return response()->json([
         'search' => $search
      ]);
        }
    }
    
    public function subscribe(Request $request)
    {
       $check=Subscribe::where('email',$request->email)->first();
       
       
       if($check==null)
       {
            $add=Subscribe::create([
            'email'=>$request->email,
            ]);
           
       }
     return back()->with('success', 'added to Subscribers list successfully');
       
    }
  
    
     public function saleproduct(Request $request)
    {
       $salemonthlyproduct=SaleMonth::first();
      
    $product1=Product::where('id' ,$salemonthlyproduct->product_id)->first(); 

         $category=Category::all(); 
         $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
         $related_products=Product::where('categories_id',$product1->categories_id)->get();
         
   
     return view('saleproduct',compact('salemonthlyproduct','cart_data','related_products','category'));
    }
   
     public function seeorderdetail($id){
       
        $seeorder = Order::where('id',$id)->first();
       
        return view('admin.seeorderdetail',compact('seeorder'));
    }
    public function delivery()
    {
       $salemonthlyproduct=SaleMonth::first();
      
    $product1=Product::where('id' ,$salemonthlyproduct->product_id)->first(); 
         $category=Category::all(); 
         $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
         $related_products=Product::where('categories_id',$product1->categories_id)->get();
   
     return view('delivery',compact('salemonthlyproduct','cart_data','related_products','category'));
    }
    
    public function review(Request $request)
    {
      
            
      
          $add=Review::create([
            'product_id'=>$request->product_id,
            'name'=>$request->name,
            'email'=>$request->email,
            'review'=>$request->message,
            'rating'=>$request->rating,
            ]);
          return back()->with('successreview','your review has been submitted');
      
     
      
            
    }
}