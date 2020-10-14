<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    public static function deleteFileIfExists($filePath)
    {
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }
    public static function deleteFileIfExistsWithDisk($filePath, $disk)
    {
        $filePath = self::removeFromPath($filePath, '/', 1);
        if (Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
        }
    }

    public static function deleteDirectoryOfFile($filePath)
    {
        $pathArray = explode('/', $filePath);
        unset($pathArray[count($pathArray) - 1]);
        $directoryPath = implode('/', $pathArray);
        Storage::disk('public-path')->deleteDirectory($directoryPath);
    }

    public static function getFileNameFromPath($filePath)
    {
        $pathArray = explode('/', $filePath);

        return $pathArray[count($pathArray) - 1];
    }

    public static function getFileSize($filePath)
    {
        return Storage::disk('public-path')->size($filePath);
    }

    public static function cleanTextForFolderName($string)
    {
        return Str::lower(preg_replace("/[\s]/", '-', preg_replace('/[^A-Za-z0-9 ]/', '', $string)));
    }

    public static function getFolderPathFromPhoto($imagePath)
    {
        $imagePathArr = explode('/', $imagePath);
        unset($imagePathArr[0]);
        $imagePathArr = array_values($imagePathArr);
        unset($imagePathArr[0]);
        unset($imagePathArr[count($imagePathArr)]);

        return '/' . implode('/', $imagePathArr) . '/';
    }

    public static function removeFromPath($path, $delimiter, $index)
    {
        $pathArray = explode($delimiter, $path);
        unset($pathArray[$index]);

        return implode("/", $pathArray);
    }
}
