<?php 

namespace marcha\Image\Providers\Laravel;

use Config;
use Illuminate\Support\ServiceProvider;
use marcha\Image\Providers\Laravel\Provider as LaravelProvider;
use marcha\Image\Image;
use marcha\Image\Providers\Laravel\Commands\MoveAssetCommand;
use marcha\Image\Cache\ProviderCacher;
use marcha\Image\SaveHandlers\FileSystem;

class ImageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	public function boot() {

        require __DIR__.'/routes.php';

        $this->publishes([
            __DIR__.'/../../../../config/config.php' => config_path('image.php'),
        ]);
	}


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

        // Config::package('marcha/image', __DIR__.'/../../../../../config');
		// config('marcha/image', __DIR__.'/../../../../config');

		$this->registerCache();
		$this->registerImageFileSaveHandler();
		$this->registerImage();

		$this->registerCommands();

	}


	private function registerCache() {

		$this->app->bind('marcha.Image.cache', function() {
            // default cache is file
            // trying to keep image cache separate from other cache
            $config = array();
            $config['config']['cache.driver'] = 'file';
            $config['config']['cache.path'] = storage_path() . '/cache/' . Config::get('image.cache.path');
            $config['files'] = new \Illuminate\Filesystem\Filesystem;
            // return new \Illuminate\Cache\CacheManager($config);
            return new \Illuminate\Cache\CacheManager($this->app);
		});

	}


	private function registerImageFileSaveHandler()
	{
		$app = $this->app;
		$this->app->bind('marcha.Image.saveHandler', function() use ($app) {
			return new FileSystem(new LaravelProvider($app['marcha.Image.cache']));
		});
	}


	private function registerImage() {

		$app = $this->app;

		$this->app->bind('marcha.Image', function() use ($app) {
			$provider = new LaravelProvider($app['marcha.Image.cache']);
			// option 1
			$cacher   = new ProviderCacher($provider);
			// option 2
			// $cacher   = new ImageFileCacher($app['marcha.Image.saveHandler']);
			return new Image($provider,
							 Config::get('image.cache.lifetime'),
							 Config::get('image.route'),
							 $cacher);
		});

	}


	private function registerCommands() {

		$this->app['command.marcha.Image.moveasset'] = $this->app->share(function($app) {
			return new MoveAssetCommand();
		});

		$this->commands('command.marcha.Image.moveasset');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('marcha.Image');
	}

}
