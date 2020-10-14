<?php

namespace App\Helpers;

use App\Image;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ImagesHelper
{
    public static function uploadImage($image, $destinationPath)
    {
        $filename = Str::lower(Carbon::now()->format('d-m-y-H-i-s') . '-' . str_replace(' ', '-', $image->getClientOriginalName()));
        $image->move(public_path() . $destinationPath, $filename);

        return $destinationPath . $filename;
    }

    public static function uploadFile($file)
    {
        $destinationPath = '/cv-documents/';
        $filename = Str::lower(Str::random(4) . '-' . str_replace(' ', '-', $file->getClientOriginalName()));
        $file->move(public_path() . $destinationPath, $filename);

        return $destinationPath . $filename;
    }

    public static function removeImagesById($model, $resource, $removedPhotosIds)
    {
        if (count($removedPhotosIds) > 0) {
            foreach ($removedPhotosIds as $imageId) {
                $image = $resource->images()->findOrFail($imageId);
                $imagePath = substr($image->image_path, 1);
//                self::removeImageIfExists($imagePath);
                FilesHelper::deleteFileIfExists($imagePath);
                $image->delete();
            }
        }
    }
}
