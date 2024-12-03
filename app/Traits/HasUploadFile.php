<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

trait HasUploadFile
{
    public function uploadFile($path, $file, $disk = 'local'): string
    {
        $filename = $this->generateFileName($file);
        $uploadedPath = Storage::disk($disk)->putFileAs($path, $file, $filename);

        return $uploadedPath;
    }

    public function deleteFile($file, $disk = 'local'): bool
    {
        return Storage::disk($disk)->delete($file);
    }

    public function updateFile($path, $file, $oldFilePath, $disk = 'local'): string
    {
        $uploadedPath = $this->uploadFile($path, $file, $disk);

        if ($uploadedPath) {
            Storage::disk($disk)->delete($oldFilePath);
        } else {
            $uploadedPath = $oldFilePath;
        }

        return $uploadedPath;
    }

    public function generateFileName(UploadedFile $file): string
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = substr($filename, 0, 20);

        $extension = $file->getClientOriginalExtension();
        $randomString = Str::random(16);
        $id = Auth::id();

        return "{$filename}+{$id}{$randomString}.{$extension}";
    }
}
