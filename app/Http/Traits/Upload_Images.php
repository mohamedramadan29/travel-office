<?php

namespace App\Http\Traits;
trait Upload_Images
{
    function saveImage($file, $path)
    {
        $file_extension = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extension;
        $file->move($path, $file_name); // Call move() on $file, not $file_name
        return $file_name;
    }

    function saveImageLivewire($file, $path)
    {
        $file_extension = $file->getClientOriginalExtension();
        $file_name = time() . '.' . $file_extension;

        // Create the directory if it doesn't exist
        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0777, true);
        }

        // Store the file temporarily in livewire-tmp
        $temporaryPath = $file->store('livewire-tmp');

        // Move the file to the desired location
        $temporaryFile = storage_path('app/' . $temporaryPath);
        $destinationPath = public_path($path . '/' . $file_name);
        if (rename($temporaryFile, $destinationPath)) {
            return $path . '/' . $file_name;
        }

        return null;
    }
}
