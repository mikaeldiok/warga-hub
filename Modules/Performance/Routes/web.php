<?php

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

/*
*
* Auth Routes
*
* --------------------------------------------------------------------
*/
/*
*
* Frontend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Performance\Http\Controllers\Frontend', 'as' => 'frontend.', 'middleware' => ['web','auth'], 'prefix' => ''], function () {

    /*
     *
     *  Performances Routes
     *
     * ---------------------------------------------------------------------
     */
    $module_name = 'parameters';
    $controller_name = 'ParametersController';    
    Route::get("$module_name/show", ['as' => "$module_name.index", 'uses' => "$controller_name@index"]);
    Route::get("$module_name/download", ['as' => "$module_name.download", 'uses' => "$controller_name@download"]);
    Route::get("$module_name/catalog/filter", ['as' => "$module_name.filterParameters", 'uses' => "$controller_name@filterParameters"]);
    Route::get("$module_name/{id}-{parameterId}", ['as' => "$module_name.show", 'uses' => "$controller_name@show"]);
});

/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['namespace' => '\Modules\Performance\Http\Controllers\Backend', 'as' => 'backend.', 'middleware' => ['web', 'auth', 'can:view_backend'], 'prefix' => 'admin'], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Parameters Routes
     *
     * ---------------------------------------------------------------------
     */

    $module_name = 'parameters';
    $controller_name = 'ParametersController';
    Route::get("$module_name/index_list", ['as' => "$module_name.index_list", 'uses' => "$controller_name@index_list"]);
    Route::get("$module_name/index_data", ['as' => "$module_name.index_data", 'uses' => "$controller_name@index_data"]);
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::delete("$module_name/purge/{id}", ['as' => "$module_name.purge", 'uses' => "$controller_name@purge"]);
    Route::post("$module_name/get_parameter", ['as' => "$module_name.getparameter", 'uses' => "$controller_name@get_parameter"]);
    Route::post("$module_name/import", ['as' => "$module_name.import", 'uses' => "$controller_name@import"]);
    Route::resource("$module_name", "$controller_name");

    $module_name = 'cores';
    $controller_name = 'CoresController';
    Route::get("$module_name/trashed", ['as' => "$module_name.trashed", 'uses' => "$controller_name@trashed"]);
    Route::patch("$module_name/trashed/{id}", ['as' => "$module_name.restore", 'uses' => "$controller_name@restore"]);
    Route::delete("$module_name/purge/{id}", ['as' => "$module_name.purge", 'uses' => "$controller_name@purge"]);
    Route::resource("$module_name", "$controller_name");

});
