<?php 

namespace marcha\Image\Cache;

use marcha\Image\Providers\ProviderInterface;
use marcha\Image\Image;

interface CacherInterface {

	public function init($imgPath, $operations, $cacheLifetime, $publicPath);

	public function exists();

	public function serve();

	public function put($data);

	public function getSrcPath();

}