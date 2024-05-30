<?php namespace marcha\Image\SaveHandlers;

use marcha\Image\Image;
use marcha\Image\Providers\ProviderInterface;

interface SaveHandlerInterface {

    public function getPublicPath();

    public function exists($filename);

    public function save($filename, array $data);

    public function getSrcPath();

    public function setPaths($imgPath, $publicPath);

    public function getSize($filename);
}