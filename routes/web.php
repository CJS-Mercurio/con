<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactNoteController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

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


Route::get('/', WelcomeController::class);
Route::resource('/contacts', ContactController::class);
Route::delete('/contacts/{contact}/restore', [ContactController::class, 'restore'])->name('contacts.restore');
Route::delete('/contacts/{contact}/force-delete', [ContactController::class, 'forceDelete'])->name('contacts.force-delete');
// Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
// Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
// Route::get('/contacts/create', [ContactController::class, 'create'])->name('contacts.create');
// Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
// Route::get('/contacts/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
// Route::put('/contacts/{id}', [ContactController::class, 'update'])->name('contacts.update');
// Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

//Resource Route
Route::resource('/companies', CompanyController::class);
Route::resources([
    '/tags' => TagController::class,
    '/tasks' => TaskController::class
]);

// Route::resource('/activities', ActivityController::class)->except([
//     'index', 'show' 
// ]);

Route::resource('/contacts.notes', ContactNoteController::class)->shallow();

// Route::resource('/activities', ActivityController::class)->names([
//     'index' => 'activities.all',
//     'show' => 'activities.view'
// ]);

Route::resource('/activities', ActivityController::class)->parameters([
    'activities' => 'active'
]);




// Route::fallback(function () {

//     return "<h1>Sorry, The page does not exist in this world!</h1>";
// });


// Route Group -> Admin
// Route::prefix('admin')->group(function () {

//     Route::get('/contacts', function () {

//         $companies = [
//             1 => ['name' => 'Company One', 'contacts' => 3],
//             2 => ['name' => 'Company Two', 'contacts' => 5],
//         ];
//         $contacts = getContacts();
//         return view('contacts.index', compact('contacts', 'companies'));

//     })->name('contacts.index');


//     Route::get('/contacts/create', function () {

//         return view('contacts.create');
//     })->name('contacts.create');


//     Route::get('/contacts/{id}', function ($id) {

//         $contacts = getContacts();
//         abort_if(!isset($contacts[$id]), 404);
//         $contact = $contacts[$id];
//         return view('contacts.show')->with('contact', $contact);
//     })->whereNumber('id')->name('contacts.show');
//     // ->where('id', '[0-9]+');
// });


// Route::get('/companies/{name?}', function ($name = null) {
    
//     if ($name) {

//         return "Company: " . $name;
        
//     } else {

//         return "All companies";
//     }

// })->whereAlphaNumeric('name');
// ->where('name', '[a-zA-Z]+');

