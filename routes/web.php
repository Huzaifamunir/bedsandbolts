<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\StripePaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('index');
//});
Route::get('/', [GeneralController::class, 'index'])->name('index');
Route::get('productpage/{id}', [GeneralController::class, 'productpage'])->name('productpage');
Route::get("/redirects",[GeneralController::class,"redirects"])->name('redirects')->middleware('auth');
Route::get('addtocart', [GeneralController::class, 'addcart'])->name('addcart');
Route::get('deletecartitem/{id}',[GeneralController::class,'deleteitem'])->name('deletecartitem');
Route::get('home', [GeneralController::class, 'home'])->name('home');
Route::get('category', [GeneralController::class, 'category'])->name('category');
Route::get('addproductdata', [GeneralController::class, 'category1'])->name('addproductdata');
//Admin panel functionalities
Route::get('option',[AdminController::class,'option'])->name('option')->middleware('auth');
Route::post('addcategory', [AdminController::class, 'addcategory'])->name('addcategory');

Route::get('fetchcategory', [AdminController::class, 'fetchcategory'])->name('fetchcategory');
Route::get('editcategory/{id}', [AdminController::class, 'editcategory'])->name('editcategory');
Route::POST('categoryupdate/{id}', [AdminController::class, 'categoryupdate'])->name('categoryupdate');
Route::delete('destroy/{id}', [AdminController::class, 'destroy'])->name('destroy');
Route::get('deletefromsale/{id}', [AdminController::class, 'deletefromsale'])->name('deletefromsale');
Route::post('addproduct', [AdminController::class, 'addproduct'])->name('addproduct');
Route::get('products', [AdminController::class, 'products'])->name('products')->middleware('auth');

//route for edit and delete  option 
Route::get('deletedbedoption/{id}', [AdminController::class, 'deletedbedoption'])->name('deletedbedoption');
Route::get('deletefabricdataoption/{id}', [AdminController::class, 'deletefabricdataoption'])->name('deletefabricdataoption');
Route::get('deletebeedingdata/{id}', [AdminController::class, 'deletebeedingdata'])->name('deletebeedingdata');  
Route::get('addonoptiondata/{id}', [AdminController::class, 'addonoptiondata'])->name('addonoptiondata'); 
Route::get('matchingstoragebox/{id}', [AdminController::class, 'matchingstoragebox'])->name('matchingstoragebox');
Route::get('deletemattressdataoption/{id}', [AdminController::class, 'deletemattressdataoption'])->name('deletemattressdataoption'); 
Route::get('sofaoptiondata/{id}', [AdminController::class, 'sofaoptiondata'])->name('sofaoptiondata');
Route::get('stooloptiondata/{id}', [AdminController::class, 'stooloptiondata'])->name('stooloptiondata');
Route::post('editoptions', [AdminController::class, 'editoptions'])->name('editoptions');

//end route for option
Route::get('editproduct/{id}', [AdminController::class, 'editproduct'])->name('editproduct');
Route::get('deleted/{id}', [AdminController::class, 'deleted'])->name('deleted');
Route::post('update/{id}', [AdminController::class, 'update'])->name('update');
Route::post('aprovedstatus', [AdminController::class, 'aprovedstatus'])->name('aprovedstatus');
Route::post('addmonthlysale', [AdminController::class, 'addmonthlysale'])->name('addmonthlysale');
Route::get('saledelete/{id}', [AdminController::class, 'saledelete'])->name('saledelete');
Route::get('contactus', [AdminController::class, 'contactus'])->name('contactus');
Route::post('contactmail', [AdminController::class, 'contactmail'])->name('contactmail');
Route::get('contactshow', [AdminController::class, 'contactshow'])->name('contactshow');
Route::get('deletepersondetail/{id}', [AdminController::class, 'deletepersondetail'])->name('deletepersondetail');

//Front End
//
Route::post('bedsize',[OptionController::class,'size'])->name('bedsize');
Route::post('addsofaoptions',[OptionController::class,'addsofaoptions'])->name('addsofaoptions');
//
Route::post('addstooloptions',[OptionController::class,'addstooloptions'])->name('addstooloptions');
Route::post('fabric_co',[OptionController::class,'fabric'])->name('fabric');
Route::post('mattresssize',[OptionController::class,'mattress'])->name('mattress');
Route::post('addon',[OptionController::class,'addon'])->name('addon');
Route::post('beeding',[OptionController::class,'beeding'])->name('beeding');
Route::post('matching_storage',[OptionController::class,'matching_storage_box'])->name('matching_storage_box');
// Route::get('optionbed',[OptionController::class,'optionbed'])->name('optionbed');
// Route::get('index', [GeneralController::class, 'index'])->name('index');
Route::post('review', [GeneralController::class, 'review'])->name('review');
Route::get('delivery', [GeneralController::class, 'delivery'])->name('delivery');
Route::get('saleproduct', [GeneralController::class, 'saleproduct'])->name('saleproduct');
Route::post('subscribe', [GeneralController::class, 'subscribe'])->name('subscribe');
Route::post('productsearch', [GeneralController::class, 'productsearch'])->name('productsearch');
Route::get('allproducts', [GeneralController::class, 'allproducts'])->name('allproducts');
    Route::get('checkout', [GeneralController::class, 'checkout'])->name('checkout');
    Route::get('order',[GeneralController::class,'order'])->name('order')->middleware('auth');
    Route::get('deleteorder/{id}', [GeneralController::class, 'deleteorder'])->name('deleteorder');
    Route::get('vieworder/{id}',[GeneralController::class, 'vieworder'])->name('vieworder');
     Route::get('seeorderdetail/{id}',[GeneralController::class, 'seeorderdetail'])->name('seeorderdetail');
    Route::post('placeorder', [GeneralController::class, 'placeorder'])->name('placeorder');
   Route::get('transationdata/{id}',[GeneralController::class,'transationdata'])->name('transationdata');
   Route::get('stripesetting',[AdminController::class,'stripesetting'])->name('stripesetting');
 Route::post('settingpay', [AdminController::class, 'settingpay'])->name('settingpay');

    Route::get('byoptions/{id}/{type}', [GeneralController::class, 'byoptions'])->name('byoptions');

Route::get('product/{id}', [AdminController::class, 'product'])->name('product');
Route::get('logout',[AdminController::class,'logout'])->name('logout');
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('stripe', [StripePaymentController::class, 'stripe'])->name('stripe');
Route::post('stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
