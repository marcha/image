<?php namespace marcha\Image\Servers;

interface ServerInterface {

	public function isFromCache();

	public function getImageData();

	public function serve();

}
