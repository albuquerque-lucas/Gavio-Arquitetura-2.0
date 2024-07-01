<?php

namespace App;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\Jpeg2000Encoder;

trait ProcessesImages
{
    public function processImage($file, $path)
    {
        $manager = new ImageManager(new Driver());
        $img = $manager->read($file);
        $img->resize(1024, 768, function ($constraint) {
            $constraint->aspectRatio();
        });
        if ($file->getClientOriginalExtension() === 'png') {
            $encoded = $img->toJpeg(65);
            $encoded->save($path, 90);
            return;
        }

        $img->save($path, 60);
        return;
    }
}
