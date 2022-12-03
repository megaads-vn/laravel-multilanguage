<?php
Route::get('/lang-editor', ['as' => 'frontend::multilanguage::editor', 'uses' => 'Megaads\MultiLanguage\Controllers\MultiLanguageController@langEditor'])->middleware('auth.lang');
Route::post('/lang-editor', ['as' => 'frontend::multilanguage::editor', 'uses' => 'Megaads\MultiLanguage\Controllers\MultiLanguageController@langEditor'])->middleware('auth.lang');
Route::delete('/lang-editor/delete-item', ['as' => 'frontend::mutilanguage::delete::item', 'uses' => 'Megaads\MultiLanguage\Controllers\MultiLanguageController@deleteItem'])->middleware('auth.lang');
Route::get('/lang-editor/resources/{file}', ['as' => 'lang-editor::resource', 'uses' => 'Megaads\MultiLanguage\Controllers\MultiLanguageController@resources']);
Route::post('/lang-editor/add-key', ['uses' => 'Megaads\MultiLanguage\Controllers\MultiLanguageController@addLanguageKey'])->middleware('auth.lang');
Route::get('/lang-editor/download', ['uses' => 'Megaads\MultiLanguage\Controllers\MultiLanguageController@downloadLanguageFile']);

Route::get('lang-editor/export', 'Megaads\MultiLanguage\Controllers\MultiLanguageController@export');
Route::post('lang-editor/import', 'Megaads\MultiLanguage\Controllers\MultiLanguageController@import');