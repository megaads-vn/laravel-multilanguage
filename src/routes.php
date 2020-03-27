<?php

Route::get('/lang-editor', ['as' => 'frontend::multilanguage::editor', 'uses' => 'Megaads\MultiLanguage\Controller\MultiLanguageController@langEditor']);
Route::delete('/lang-editor/delete-item', ['as' => 'frontend::mutilanguage::delete::item', 'uses' => 'Megaads\MultiLanguage\Controller\MultiLanguageController@deleteItem']);