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
        Log::info("test", $request->allFiles());

        foreach ($request->allFiles() as $photo) {

            $filename = $request["name" . $count];
            Log::info("test", (array)$filename);
            $path = $photo->store('public/photos');
            $file = File::create([
                'path' => $path,
                'filename' => strval($filename),
                'authorId' => Auth::id()
            ]);

            $photo->move(public_path('images/photos'),  strval($filename));

            $file->save();
            $count = $count + 1;

        }
        return $this->sendResponse("ok");
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
