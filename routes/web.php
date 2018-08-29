<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('pdf', ['uses' => 'PdfController@monthView']);

// $router->get('pdf', ['uses' => 'PdfController@index']);



$router->group(['prefix' => 'api'], function() use ($router) {
  $router->get('employee', ['uses' => 'EmployeeController@index']);
  $router->get('employee/{id}', ['uses' => 'EmployeeController@view']);
  $router->post('employee', ['uses' => 'EmployeeController@store']);
  $router->patch('employee/{id}', ['uses' => 'EmployeeController@update']);
  $router->delete('employee/{id}', ['uses' => 'EmployeeController@delete']);

  $router->get('schedule', ['uses' => 'ScheduleController@index']);
  $router->post('schedule', ['uses' => 'ScheduleController@store']);
  $router->patch('schedule/{id}', ['uses' => 'ScheduleController@update']);

  $router->get('position', ['uses' => 'PositionController@index']);
  $router->get('position/{id}', ['uses' => 'PositionController@view']);
  $router->post('position', ['uses' => 'PositionController@store']);
  $router->patch('position/{id}', ['uses' => 'PositionController@update']);
  $router->delete('position/{id}', ['uses' => 'PositionController@delete']);

  $router->get('schedule/{id}', ['uses' => 'ScheduleController@view']);
  $router->patch('schedule/{id}', ['uses' => 'ScheduleController@update']);
  $router->delete('schedule/{id}', ['uses' => 'ScheduleController@delete']);

  $router->get('schedule-data', ['uses' => 'ScheduleDataController@index']);
  $router->get('schedule-data/{id}', ['uses' => 'ScheduleDataController@view']);
  $router->patch('schedule-data/{id}', ['uses' => 'ScheduleDataController@update']);

  $router->get('settings', ['uses' => 'SettingsController@index']);
  $router->post('settings', ['uses' => 'SettingsController@update']);

  $router->get('holidays', ['uses' => 'HolidaysController@index']);
  $router->post('holidays', ['uses' => 'HolidaysController@update']);

  $router->post('pdf/month', ['uses' => 'PdfController@month']);

  $router->post('report', ['uses' => 'ReportsController@store']);
});
