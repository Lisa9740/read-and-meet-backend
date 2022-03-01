<?php

namespace App\Http\Controllers\API;


use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageController extends BaseController
{

    public function savePostImages(Request $request)
    {
        $count = 1;

        foreach ($request->allFiles() as $photo) {
            $filename = $request["name" . strval($count)];
            Log::info("test", (array)strval($count));
            $path = $photo->store('public/photos');
            File::create([
                'path' => $path,
                'filename' => strval($filename),
                'authorId' => Auth::id()
            ]);
            $count = $count + 1;
        }
        echo "Upload Successfully";
    }

    public function getPostImage(Request $request, String $url)
    {
        Log::info("test", (array)$url);
       $filename = $url;
       $files = DB::table('files')->where('filename', $filename)->get();
        Log::info("test", (array)$files);

       return $this->sendResponse(new FileResource($files));
    }



}
