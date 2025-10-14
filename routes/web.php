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


route::get("/", [PageControl::class,"index"])->name("home");
route::get("/login", [PageControl::class,"login"])->name("login");
route::get("/register", [PageControl::class,"register"])->name("register");

route::post("/login", [DataControl::class,"login"])->name("login.post");
route::post("/register", [DataControl::class,"register"])->name("register.post");
route::get("/logout", [DataControl::class,"logout"])->name("logout");
route::get("/delete/{table}/{id}",[DataControl::class,"delete"])->name("delete");

route::get("/product", [PageControl::class,"product"])->name("products");
route::get("/product/tambah", [PageControl::class,"tambahproduct"])->name("tambahproduct");
route::post("/product/tambah", [DataControl::class,"tambahproduct"])->name("tambahproduct.post");
route::get("/product/{id}", [PageControl::class,"editproduct"])->name("editproduct");
route::post("/product/{id}", [DataControl::class,"editproduct"])->name("editproduct.post");

route::get("/user", [PageControl::class,"user"])->name("users");
route::get("/user/tambah", [PageControl::class,"tambahuser"])->name("tambahuser");
route::post("/user/tambah", [DataControl::class,"tambahuser"])->name("tambahuser.post");
route::get("/user/{id}", [PageControl::class,"edituser"])->name("edituser");
route::post("/user/{id}", [DataControl::class,"edituser"])->name("edituser.post");

route::get("/monitor/category",[PageControl::class,"monitorcategory"])->name("MC");
route::get("/monitor/role",[PageControl::class,"monitorrole"])->name("MR");