<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageControl;
use App\Http\Controllers\DataControl;
use App\Http\Controllers\MessageController;

Route::get('/chat', [MessageController::class, 'start'])->name('chat.index');
Route::post('/chat', [MessageController::class, 'store'])->name('chat.store');

Route::get('/cart/add/{id}', [DataControl::class, 'add'])->name('cart.add');
Route::get('/cart', [PageControl::class, 'cart'])->name('cart.index');
Route::get('/cart/remove/{id}', [DataControl::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [DataControl::class, 'checkout'])->name('cart.checkout');


route::get("/", [PageControl::class, "index"])->name("home");
route::get("/login", [PageControl::class, "login"])->name("login");
route::get("/register", [PageControl::class, "register"])->name("register");

route::post("/login", [DataControl::class, "login"])->name("login.post");
route::post("/register", [DataControl::class, "register"])->name("register.post");
route::get("/logout", [DataControl::class, "logout"])->name("logout");
route::get("/delete/{table}/{id}", [DataControl::class, "delete"])->name("delete");
route::get("/detaildelete/{table}/{id}", [DataControl::class, "detaildelete"])->name("detaildelete");

route::get("/product", [PageControl::class, "product"])->name("products");
route::get("/product/tambah", [PageControl::class, "tambahproduct"])->name("tambahproduct");
route::post("/product/tambah", [DataControl::class, "tambahproduct"])->name("tambahproduct.post");
route::get("/product/{id}", [PageControl::class, "editproduct"])->name("editproduct");
route::post("/product/{id}", [DataControl::class, "editproduct"])->name("editproduct.post");
route::get("/product/create/{id}", [PageControl::class, "tambahproduct"])->name("tambahproduct");

route::get("/user", [PageControl::class, "user"])->name("users");
route::get("/user/{id}", [DataControl::class, "resetpassword"])->name("resetpassword");

route::get("/order", [PageControl::class, "order"])->name("orders");
Route::get('/orders/detail/{id}', [PageControl::class, 'orderdetail'])->name('order.detail');

route::get("/customer", [PageControl::class, "customer"])->name("customers");
route::get("/customer/{id}", [PageControl::class, "detailcustomer"])->name("customer.detail");
route::get("edit/customer/{id}", [PageControl::class, "editcustomer"])->name("customer.edit");
route::post("/customer", [DataControl::class, "editcustomer"])->name("customers.post");

route::get("/restock", [PageControl::class, "restock"])->name("restocks");
route::get("/restock/tambah", [PageControl::class, "restockadd"])->name("restock.add");
route::post("/restock", [DataControl::class, "restock"])->name("restocks.post");

route::get("/monitor/log", [PageControl::class, "viewLogs"])->name("ML");

route::get('sales/report', [PageControl::class, 'salesReport'])->name('sales.report');
route::get('sales/export/pdf', [PageControl::class, 'exportPDF'])->name('sales.export.pdf');
Route::get('/sales/export/excel', [DataControl::class, 'exportExcel'])->name('sales.export.excel');
Route::get('/sales/export/csv', [DataControl::class, 'exportCsv'])->name('sales.export.csv');


Route::get('/shop', [PageControl::class, 'shop'])->name('shop');
Route::get('/seller/detail/{id}', [PageControl::class, 'sellerdetail'])->name('seller.detail');

Route::get('/seller', [PageControl::class, 'seller'])->name('seller');
route::get("edit/seller/{id}", [PageControl::class, "editseller"])->name("seller.edit");
route::post("/seller", [DataControl::class, "editseller"])->name("seller.post");

Route::post('/feedback/add', [PageControl::class, 'addFeedback']);
Route::get('/productdetail/{id}', [PageControl::class, 'productDetail']);

Route::get('/seller/shop/{id}', [PageControl::class, 'manage'])->name('seller.shop.manage');
Route::post('/seller/shop/update/{id}', [DataControl::class, 'updateShop'])->name('seller.shop.update');

// Create new shop
Route::get('/seller/create/shop', [PageControl::class, 'createShop'])->name('seller.shop.create');
Route::post('/seller/shop/store', [DataControl::class, 'storeShop'])->name('seller.shop.store');
Route::get('/proceed/{id}', [DataControl::class, 'proceedPayment'])->name('pay');

// Payment process
Route::get('/order/{id}/payment', [DataControl::class, 'create'])->name('payment.create');
Route::post('/order/{id}/payment', [DataControl::class, 'store'])->name('payment.store');
//Route::get('/seller/payments', [DataControl::class, 'payments'])->name('seller.payments');
//Route::post('/seller/payments/{id}/update', [DataControl::class, 'updatePayment'])->name('seller.payment.update');
