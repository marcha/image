<?php

$route = rtrim(Config::get('image.route'), '/');
// dd($route);
Route::get($route, function() {
	$this->app->make('marcha.Image')->serve();
});
