<?php


use App\Helpers\Helper;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/data', function () {
    Helper::hello();

		// $data = DB::table('confirmations')->where('id',33)->first();//this is hide

		

  //       $modeldata = json_decode($data->data);

  //       //echo "<pre>"; print_r($modeldata); die;

  //       if ($modeldata->name == 'Create') {
  //           $newmodel = new $modeldata->model_type;
  //       }else{
  //           $newmodel = $modeldata->model_type::find($modeldata->model_id);
  //       }

  //       echo "<pre>"; print_r($newmodel); die;
        

  //       $chagesdataobj = $modeldata->changes;

        

  //       $changedataarr = json_decode(json_encode($chagesdataobj), true);

  //       echo "<pre>"; print_r($newmodel); die;
  //       //echo "<pre>"; print_r($chagesdata); die;

  //       foreach ($changedataarr as $key => $value) {
  //           $newmodel->$key = $changedataarr[$key];
  //       }

  //       $newmodel->save();

    //$data = DB::table('action_events')->where('model_type','App\Occasion')->where('model_id',1)->get()->last();

    //$data = DB::table('action_events')->where('model_type','App\Occasion')->where('model_id',1)->get()->last();

    // $data = DB::table('confirmations')->where('id',9)->first();

    // $modeldata = json_decode($data->data);

    // $eventdata = DB::table('action_events')->where('model_type',$modeldata->model_type)->where('model_id',$modeldata->model_id)->get()->last();

    // $newmodel = $modeldata->model_type::find($modeldata->model_id);

    // $chagesdata = json_decode($eventdata->changes,true);



    // foreach ($chagesdata as $key => $value) {
    // 	$newmodel->$key = $chagesdata[$key];
    // }

    // $newmodel->save();
    
    // echo "<pre>"; print_r($chagesdata); die;



    

    //  = $changes->title;

    

    

    
    
    
});

Route::post('provider_add', 'ProviderController@addProvider');
Route::post('provider_edit', 'ProviderController@editProvider');
Route::get('order', 'OrderController@index');
Route::put('order/{id}', 'OrderController@update');
Route::get('service', 'ServiceController@index');
Route::post('service', 'ServiceController@store');
Route::delete('service/{id}', 'ServiceController@destroy');
Route::put('service/{id}', 'ServiceController@update');
Route::get('service_options/{service_id}', 'ServiceOptionsController@index');
Route::post('service_options/{service_id}', 'ServiceOptionsController@store');
Route::put('service_options/{service_id}/{id}', 'ServiceOptionsController@update');
Route::delete('service_options/{service_id}/{id}', 'ServiceOptionsController@destroy');
Route::get('featured_main', 'FeaturedMainController@index');
Route::get('featured/{type}', 'FeaturedInPlanningEvents@index');
Route::get('occasions', 'OccasionController@index');
Route::get('av', 'ProvidersAvailabilityController@index');
Route::get('categories', 'CategoriesController@index');
Route::get('provider/{id}', 'ProviderController@providerByID');
Route::post('cat_provider_count', 'CatProviderCountController@index');
Route::post('providers_by_occ_and_time', 'ProvidersByOccAndTime@index');
Route::post('providers_by_occ_and_time_cat', 'ProvidersByOccAndTimeCat@index');
Route::post('venues_count_and_planners', 'VenuesCountAndPlanners@index');
Route::get('providers', 'ProviderController@index');

Route::get('providers_by_cat/{catID}', 'ProvidersByCatController@index');
// Auth::routes();

// Route::resource('reset_pass', 'ResetPasswordController');

// Route::get('/reset_pass', function () {
//     return view('reset_password');
// });

Route::get('reset_pass', 'ResetPasswordController@index')->name('reset_pass.index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



Route::group([
    'prefix' => 'auth'
], function () {  
    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('/users', 'UserController@getuserData');
 
		Route::get('/user/{id}', 'UserController@updateuserData');
        // Route::post('provider_add', 'ProviderController@addProvider');
    });
});

Route::get('/user/{id}', 'UserController@updateuserData');

 
Route::post('/update-userdata', 'HomeController@updateuserData'); 
