<?php

namespace App;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

trait ProcessesImages
{
    public function processImage($file, $path)
    {
        $manager = new ImageManager(new Driver);
        $img = $manager->read($file);
        if ($file->getClientOriginalExtension() === 'png') {
            $encoded = $img->toJpeg(65);
            $encoded->save($path, 90);

            return;
        }

        $img->save($path, 60);

    }
}
