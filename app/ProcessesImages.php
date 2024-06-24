<?php

namespace App;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait ProcessesImages
{
    public function processImage($file, $path)
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($file);
        $img->resize(1024, 768);
        $img->save($path, 60);
    }
}
