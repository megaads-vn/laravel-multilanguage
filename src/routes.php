<?php
Route::get('/lang-editor', ['as' => 'frontend::multilanguage::editor', 'uses' => 'Megaads\MultiLanguage\Controller\MultiLanguageController@langEditor'])->middleware('auth.lang');
Route::post('/lang-editor', ['as' => 'frontend::multilanguage::editor', 'uses' => 'Megaads\MultiLanguage\Controller\MultiLanguageController@langEditor'])->middleware('auth.lang');
Route::delete('/lang-editor/delete-item', ['as' => 'frontend::mutilanguage::delete::item', 'uses' => 'Megaads\MultiLanguage\Controller\MultiLanguageController@deleteItem'])->middleware('auth.lang');