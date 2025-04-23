<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\QueueProductsController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\Quote;
use Illuminate\Support\Facades\Route;


Route::view('/welcome','welcome')->name('home');
Route::get('/browse',[ProjectController::class,'index'])->name('browse');
//providers
Route::get('/providers',[ProviderController::class,'index'])->name('providers');
Route::post('providers/create',[ProviderController::class,'store'])->name('provider-save');
Route::delete('/providers/delete/{id}', [ProviderController::class, 'destroy'])->name('provider-delete');
Route::patch('/providers/update',[ProviderController::class,'update'])->name('provider-update');

//login
Route::view('/','login')->name('login');
Route::post('/', [UserController::class, 'login'])->name('login_validation');
Route::get('/quote/addproduct',[ProductController::class,'consult'])->name('quoteproducts');

//detalle cotización
Route::get('/quote/{id}',[QueueProductsController::class,'index'])->name('quote-detail');
Route::get('/quote/detailed/{id}',[QueueProductsController::class,'consult'])->name('quote-detail-work');

//Cotización
Route::get('/quote',[QuoteController::class,'index'])->name('quote');
Route::post('/quote',[QuoteController::class,'store'])->name('quote-save');
Route::get('/quotes/{id}/data', [QuoteController::class, 'edit'])->name('quotes-edit');
Route::get('/quote/export/{quote}',[QuoteController::class,'export'])->name('quote-export');
Route::patch('/quote/update',[QuoteController::class,'update'])->name('quote-update');
Route::delete('quote/delete/{id}',[QuoteController::class,'destroy'])->name('quote-delete');
//Cliente

Route::get('/clients',[ClientController::class,'index'])->name('clients');
Route::post('clients/create',[ClientController::class,'store'])->name('client-save');
Route::delete('clients/{client}',[ClientController::class,'destroy'])->name('client-delete');
Route::patch('clients/update',[ClientController::class,'update'])->name('client-update');

//Project

Route::get('projects/{project}',[ProjectController::class,'consult'])->name('projects-consult');
Route::get('projects/detail/{projectDetail}',[ProjectController::class,'consultDetail'])->name('projects-detail');
Route::post('projects/create',[ProjectController::class,'store'])->name('project-save');
Route::patch('projects/update',[ProjectController::class,'update'])->name('project-update');
Route::delete('projects/delete/{id}',[ProjectController::class,'destroy'])->name('project-delete');

//status
route::get('/status',[StatusController::Class,'index'])->name('status');

//dashboard

Route::view('/dashboard','dashboard')->name('dashboard');
Route::get('/dashboard/status',[DashboardController::Class,'proj_status'])->name('proj-status');
Route::get('/dashboard/clients',[DashboardController::Class,'proj_clients'])->name('proj-clients');
Route::get('/dashboard/month',[DashboardController::Class,'proj_month'])->name('proj-month');
Route::get('/dashboard/quotes',[DashboardController::Class,'quotes_with_no_projects'])->name('proj-quotes');

//products
Route::get('/products',[ProductController::class,'index'])->name('products');
Route::get('/products/descargarplantilla', [ProductController::class, 'descargarPlantilla'])->name('descargarPlantilla');
Route::post('/products/upload', [ProductController::class, 'upload'])->name('prod-upload');
Route::patch('products/single/upload',[ProductController::class,'singleUpload'])->name('product-single-upload');
Route::delete('products/{id}',[ProductController::class,'destroy'])->name('product-delete');
//administration

Route::get('/administration',[UserController::class, 'index'])->name('administration');
Route::post('user/add',[UserController::class, 'addUser'])->name('user-save');
Route::delete('user/delete/{id}',[UserController::class, 'destroy'])->name('user-delete');
Route::get('/role',[UserController::class, 'consultRole'])->name('role');
Route::get('user/detail/{userDetail}',[UserController::class, 'consultUserDetail'])->name('user-detail');
Route::patch('user/update',[UserController::class, 'updateUser'])->name('user-update');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

//reminders

Route::get('/reminders',[ReminderController::class, 'index'])->name('reminders');
Route::delete('reminders/delete/{id}',[ReminderController::class,'destroy'])->name('reminder-delete');
