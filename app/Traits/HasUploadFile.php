<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasUploadFile
{
    /**
     * Uploads a file to the specified storage disk.
     *
     * @param  string  $path  The path where the file should be stored.
     * @param  \Illuminate\Http\UploadedFile  $file  The file to upload.
     * @param  string  $disk  The storage disk to use (default is 'local').
     * @return string|false The path of the uploaded file.
     */
    public function uploadFile($path, $file, $disk = 'local'): string|false
    {
        $filename = $this->generateFileName($file);
        $uploadedPath = Storage::disk($disk)->putFileAs($path, $file, $filename);

        return $uploadedPath;
    }

    /**
     * Deletes a file from the specified storage disk.
     *
     * @param  string  $file  The path of the file to delete.
     * @param  string  $disk  The storage disk to use (default is 'local').
     * @return bool True if the file was deleted successfully, false otherwise.
     */
    public function deleteFile($file, $disk = 'local'): bool
    {
        return Storage::disk($disk)->delete($file);
    }

    /**
     * Updates a file by uploading a new version and deleting the old one.
     *
     * @param  string  $path  The path where the new file should be stored.
     * @param  \Illuminate\Http\UploadedFile  $file  The new file to upload.
     * @param  string  $oldFilePath  The path of the old file to delete.
     * @param  string  $disk  The storage disk to use (default is 'local').
     * @return string|false The path of the newly uploaded file, or false if the upload failed.
     */
    public function updateFile($path, $file, $oldFilePath, $disk = 'local'): string|false
    {
        $uploadedPath = $this->uploadFile($path, $file, $disk);

        if ($uploadedPath) {
            Storage::disk($disk)->delete($oldFilePath);
        } else {
            $uploadedPath = $oldFilePath;
        }

        return $uploadedPath;
    }

    /**
     * Generates a unique filename for a file.
     *
     * @param  \Illuminate\Http\UploadedFile  $file  The file for which to generate a name.
     * @return string A unique filename combining the original name, user ID, and a random string.
     */
    public function generateFileName(UploadedFile $file): string
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = Str::kebab(substr($filename, 0, 20));

        $extension = $file->getClientOriginalExtension();
        $randomString = Str::random(16);
        $id = Auth::id();

        return "{$filename}+{$id}{$randomString}.{$extension}";
    }
}
