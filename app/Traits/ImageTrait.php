<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use App\Services\UserService;
use App\Models\User;

trait ImageTrait {
    static public $dir = "public/pictures";

    /**
     * Add file to storage.
     *
     * @return void
     */
    function addFile($file)
    {
        $userService = new UserService(new User() ,request());
        $file_name = $userService->upload($file);
        
        $this->attributes['photo'] = $file_name;
    }

    /**
     * Delete file from storage if exists.
     *
     * @return void
     */
    public function deleteImageIfExist()
    {
        $dir = ImageTrait::$dir;
        if($this->photo) {
            $path = "{$dir}/$this->photo";
            if(Storage::exists($path))
                Storage::delete($path);
        }
    }
}