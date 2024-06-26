<?php 

namespace marcha\Image\Providers\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Image extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'marcha.Image';
	}

}
