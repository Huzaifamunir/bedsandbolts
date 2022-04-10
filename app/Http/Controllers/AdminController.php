<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Bedsize;
use App\Models\Fabric;
use App\Models\Beeding;
use App\Models\Addon;
use App\Models\User;
use App\Models\Order;
use App\Models\SofaOptions;
use App\Models\StoolOptions;
use App\Models\StripeSetting;
use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use App\Models\Mattresssize;
use App\Models\Matching_storage;
use App\Models\Transation;
use Illuminate\Support\Facades\File;
use App\Models\Subscribe;
use App\Models\SaleMonth;
use Illuminate\Support\Facades\Mail;
use App\Mail\sendmail;
use App\Mail\adminside;
use Image;
class AdminController extends Controller
{
    public function addcategory(Request $request){
        
       $model=new Category();
       $model->name=$request->post('name');
       $model->description=$request->post('description');
       if($request->hasfile('image')){
        $file=$request->file('image');
        $extension=$file->getClientOriginalName();
        $filename=time().'.'. $extension;
        $file->move('upload/img/',$filename);
        $model->image=$filename;
      }
       $model->save();
     return back();
    }
    public function fetchcategory(){
       
      $category=Category::all();
      return response()->json([
         'name'=>$category,
      ]);
    }
    public function categoryupdate(Request $request, $id){
      $category=Category::where('id',$request->id)->first();
      $category->name=$request->name;
      $category->description=$request->description;
      $category->save();
              return response()->json([
                'name'=>"updated",
             ]);
    }
      public function destroy($id){
   
      $del=Category::find($id);
       $del->delete();
    
      return response()->json([
          'success' => 'Record deleted successfully!'
      ]);
  }
  public function addproduct(Request $request){
      
         $this->validate($request, [
            'image' => 'required',
         
        ]);

 $image= array();

     if($request->file('image')) {
      foreach ($request->file('image') as $file) {
           $filename = $file->getClientOriginalName();
           $image_resize= Image::make($file->getRealPath());
           $image_resize->resize(200,200);
           $file->move('upload/img/',$filename);
          $image[] = $filename;
        
          
       }  
             
     }

    
  
    

    
    
    $image_name=json_encode($image);
  
  

  $addproduct=Product::create([
   'categories_id'=>$request->cid,
   
      'name'=> $request->name,
      'image' =>$image_name,
      'price' => $request->price1,
      'description' => $request->description,
      'bedsize_id' => json_encode($request->bedsize),
      'fabric_id' => json_encode($request->febcolor),
      'mattress_id'=>json_encode($request->mattresssize),
      'sofa_id'=>json_encode($request->sofa),
      'stool_id'=>json_encode($request->stool),
      'beeding_id'=>json_encode($request->beadingcolor),
      'addon_id'=>json_encode($request->addon),
      'is_featured'=>$request->featured,
      'is_sale'=>$request->sale,
      'liftup_storage'=>$request->liftup_storage,
      'matching_storage_id'=>json_encode($request->storagebox),
]);

// $subscriber=Subscribe::all();
// foreach($subscriber as $subscribe)
// {
//     if($request->sale=='yes')
//     {
//           \Mail::to($subscribe->email)->send(new\App \Mail\Sale($addproduct));
//     }
// }

// $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
// $product=Product::all();
  
    return back()->with('productsuccess','Product Added')->with('success','add product successfully');

  }

  public function products(){
     
    $products=Product::with('categories')->get();
   
   
    $saleproducts=Product::where('is_sale','yes')->get();
  
    $monthlysale=SaleMonth::first();
   
   return view('admin.products', compact('products','saleproducts','monthlysale'));
  }
  public function editproduct($id){
   $product=Product::find($id);
    
    //$product=Product::where('id',$id)->get();
   
    $bed=Bedsize::all();
    $category=Category::all(); 
    $fabrics=Fabric::all();
    $beedings=Beeding::all();
    $addons=Addon::all();
    $mattress=Mattresssize::all();
    
    $matching_storages=Matching_storage::all();
    
     return view('admin.productupdate',compact('category','product','bed','fabrics','beedings','addons','matching_storages','mattress'));
  }
  public function deleted($id){
   
    $del=Product::find($id);
     $del->delete();
  
    return response()->json([
        'success' => 'Record deleted successfully!'
    ]);
}
  

public function update(Request $request, $id){
  
  $update=Product::find($id);
  
  $image= array();
if($request->image!=null){
  if($request->file('image')) {   
    foreach ($request->file('image') as $file) {
           $filename = $file->getClientOriginalName();      
     $image_resize= Image::make($file->getRealPath());
           $image_resize->resize(200,200);
           $file->move('upload/img/',$filename);
           $image[] = $filename;   
        
          
        }  
             
      }
      $image_name=json_encode($image);
  
}else{

  $image_name=$update->image;

}
    
    
     
   
     $id= $request->cid;
     
   
     $update->name= $request->name;
     $update->categories_id=$id;
     $update->image=$image_name;
     $update->price = $request->price1;
     $update->description =$request->description;
     $update->bedsize_id = json_encode($request->bedsize);
     $update->fabric_id = json_encode($request->febcolor);
     $mattress_id =json_encode($request->mattresssize);
     $update->beeding_id = json_encode($request->beadingcolor);
     $update->addon_id=json_encode($request->addon);
     $update->is_featured=$request->featured;
     $update->liftup_storage=$request->liftup_storage;
     
     $update->matching_storage_id=json_encode($request->storagebox);
   
   
    $update->update();
    


return redirect('products');
   
}

///Product page on front end
public function product($id){
    
  $product=Product::where('categories_id',$id)->paginate(20); 
  $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
  $category=Category::all(); 
  $monthlysale=SaleMonth::first();
  return view('product',compact('product','cart_data','category','id','monthlysale'));
}
  public function option(){
    
      $bedsize=Bedsize::all();
      $fabricdata=Fabric::all();
      $beedingdata=Beeding::all();
      $addondata= Addon::all();
      $sofa_data=SofaOptions::all();
      $mattress=Mattresssize::all();
     
       $stool_data=StoolOptions::all(); 
      $matchingdata=Matching_storage::all();
    return view('admin.option',compact('bedsize','fabricdata','beedingdata','addondata','matchingdata','sofa_data','stool_data','mattress'));
  }
  public function deletefromsale($id){
     $deletefromsale=Product::where('id', $id)->first();
     $deletefromsale->is_sale='0';
     $deletefromsale->save();
     return back();
      
  }
  
   public function aprovedstatus(Request $request){
      
        $cahngestatus = Order::where('id',$request->id)->first();

       $cahngestatus->status=$request->value;
       $cahngestatus->save();
       $order = Order::where('id','=',$request->id)->first();
       
         \Mail::to($order->email)->send(new\App \Mail\Invoice($order));
         if($request->value=='1')
         {
         return back()->with('success','pay successfully');    
         }else
         {
             return back()->with('danger','pending');
         }
         
       
   
       }
       public function deletedbedoption($id){
            $bedoption=Bedsize::find($id);
       $bedoption->delete();
       return redirect()->route('option');
           
           
       }
       public function  deletefabricdataoption($id){
          
           
            $fabricption=Fabric::find($id);
           
       $fabricption->delete();
      return redirect()->route('option')->with('danger'
           ,'Fabric  delete  successfully');
           
       }
         public function  deletebeedingdata($id){
          
           
            $fabricption=Beeding::find($id);
           
       $fabricption->delete();
       return redirect()->route('option')->with('danger'
           ,'Beeding  delete  successfully');;
           
           
       }
       public function deletemattressdataoption($id){
           $mattress= Mattresssize::find($id);
           $mattress->delete();
             return redirect()->route('option')->with('danger'
           ,'Mattress  delete  successfully');
       }
       
       
       
       
       
               public function  addonoptiondata($id){
          
           
            $fabricption=Addon::find($id);
           
       $fabricption->delete();
      return redirect()->route('option')->with('danger'
           ,'Addon  delete  successfully');
           
       }            
         public function  matchingstoragebox($id){
          
           
            $msb=Matching_storage::find($id);
           
       $msb->delete();
      return redirect()->route('option')->with('danger'
           ,'Matching_storage  delete  successfully');
           
           
       }    
              public function  stooloptiondata($id){
          
           
            $msb=StoolOptions::find($id);
           
       $msb->delete();
      return redirect()->route('option')->with('danger'
           ,'Stool Product  delete  successfully');
           
           
       }
                public function  sofaoptiondata($id){
          
           
            $msb=SofaOptions::find($id);
           
       $msb->delete();
      return redirect()->route('option')->with('danger'
           ,'Sofa product  delete  successfully');
           
           
       }
       public function editoptions(Request $request){
        
           if($request->type=="bedsize")
           {
               $updatebedsize=Bedsize::where('id',$request->bedsize_id)->first();
               $updatebedsize->name=$request->bedsize;
              
               $updatebedsize->save();
               return back()->with('success'
           ,'bed product edit   successfully');
               
           }
             else if($request->type=="sofa")
           {
               $updatesofa=SofaOptions::where('id',$request->sofa_id)->first();
               $updatesofa->name=$request->sofa;
               $updatesofa->save();
                return back()->with('success'
           ,'sopa product edit   successfully');
           }
           else if($request->type=="stool")
           {
               $updatestool=StoolOptions::where('id',$request->stool_id)->first();
               $updatestool->name=$request->stool;
               $updatestool->save();
                return back()->with('success'
           ,'stool product edit   successfully');
           }
           
           else if($request->type=="fabric"){
               $updatefabric=Fabric::where('id',$request->fabric_id)->first();
               $updatefabric->name=$request->fabric_co;
               $updatefabric->price=$request->fabric_price;
               $updatefabric->save();
                return back()->with('success'
           ,'fabric product edit   successfully');
           }
           
            else if($request->type=="mattress"){
               $updatefabric=Mattresssize::where('id',$request->mattress_id)->first();
               $updatefabric->name=$request->mattress_name;
               $updatefabric->price=$request->mattress_price;
               $updatefabric->save();
                return back()->with('success'
           ,'mattress product edit   successfully');
           }
           
           
           else if($request->type=="beeding"){
               $updatebeeding=Beeding::where('id',$request->beeding_id)->first();
               $updatebeeding->name=$request->beeding_name;
               $updatebeeding->price=$request->beeding_price;
               $updatebeeding->save();
                return back()->with('success'
           ,'Beeding product edit   successfully');
           }
            else if($request->type=="addon"){
               $updateaddon=Addon::where('id',$request->addon_id)->first();
               $updateaddon->name=$request->addon_name;
               $updateaddon->price=$request->addon_price;
               $updateaddon->save();
                return back()->with('success'
           ,'addon product edit   successfully');
           }
            else if($request->type=="matchingstoragebox"){
               $updateaddon=Matching_storage::where('id',$request->msb_id)->first();
               $updateaddon->name=$request->msb_name;
               $updateaddon->price=$request->msb_price;
               $updateaddon->save();
                return back()->with('success'
           ,'matchingstorage product edit   successfully');
           }
       }
       
     
       
         
          public function addmonthlysale(Request $request){
                
            $check=SaleMonth::count();
            
            // if($check==1)
            // {
              
            //     return back()->with('danger','Sale Already added');
            // }else
            // {
            if($check==1)
             {
                 $delete=SaleMonth::first();
                $delete->delete();
             }
                
                $add=SaleMonth::create([
                    'sale_name'=>$request->salename,
                    'product_id'=>$request->product,
                    'discount'=>$request->discount,
                    'sale_month'=>$request->sale_date,
                    'sale_description'=>$request->sale_description,
                    ]);
                    
                    $subscriber=Subscribe::all();
foreach($subscriber as $subscribe)
{
    
           \Mail::to($subscribe->email)->send(new\App \Mail\Sale($add));
    
}
                    return back()->with('success','Sale Added');
                    
            // }
          }
          
          public function saledelete($id)
          {
              
              $delete=SaleMonth::where('id',$id)->delete();
               return back()->with('danger','Sale Deleted');
          }
          public function contactus(){
                    $category= Category::all();
                     $cart_data =  Cart::where('user_id','=',session('dummy_session_id'))->get();
              return view('contact',compact('category','cart_data'));
          }
          public function contactmail(Request $request){
              
                 $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            
            'phone' => 'required',
            'message' => 'required'
        ]);
        
              $contact=  new Contact;
              $contact->name= $request->name;
              $contact->email= $request->email;
              $contact->phone= $request->phone;
              $contact->message= $request->message;
              $contact->save();
              
                 $sendmail=array(
        
            'id'=>$contact->id,
              'name'=>$contact->name,
        );
           
              
              
              Mail::to($request->email)->send(new sendmail($sendmail));
            
              return back()->with('success', 'person detail subbmitted');
          }
          public function contactshow(){
              $con= Contact::all();
             
              return view('admin.contactmail',compact('con'));
          }
          public function deletepersondetail($id){
              $delete = Contact::find($id);
              $delete->delete();
              return back()->with('danger','person detail delete');
          }
          public function logout(){
              Session::flush();
              Auth::logout();
              return redirect('login');
          }
          public function stripesetting(){
            $payment= StripeSetting::all();
            return view('admin.stripesetting',compact('payment'));
            
          }
          public function settingpay(Request $request){
            
              $order= new StripeSetting;
              $order->token= $request->token;
              $order->secrete_key= $request->secrete_key;
              $order->status= '0';
              $order->save();
              return redirect()->route('stripesetting');
              
          }
}
