<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResizeImageRequest;
use App\Models\ImageManipulation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Models\Album;

class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ResizeImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function resize(ResizeImageRequest $request)
    {
        $all = $request->all();
        
        /** @var UploadedFile|string file or Url $image */
        $image = $all['image'];
        
        //unseting image from all because $all will be used to save data to database
        //image does not exist in database
        unset($all['image']);
        
        $data = [
            'type' => ImageManipulation::TYPE_RESIZE,
            'data' => json_encode($all),
            'user_id' => null
        ];
        
        if (isset($all['album_id'])) {
            //TODO after implementing authentication
            
            $data['album_id'] = $all['album_id'];
        }
        
        $dir = 'images/'.\Illuminate\Support\Str::random().'/';
        $absolutePath = public_path($dir);
        
        File::makeDirectory($absolutePath);
        
        if ($image instanceof UploadedFile) {
            $data['name'] = $image->getClientOriginalName();
            
            $filename = pathinfo($data['name'], PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $originalPath = $absolutePath.$data['name'];
            
            $image->move($absolutePath, $data['name']);
        } else {
            $data['name'] = pathinfo($image, PATHINFO_BASENAME);            
            $filename = pathinfo($image, PATHINFO_FILENAME);
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $originalPath = $absolutePath.$data['name'];
            
            copy($image, $absolutePath.$data['name']);
        }
        $data['path'] = $dir.$data['name'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function show(ImageManipulation $imageManipulation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageManipulation  $imageManipulation
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageManipulation $imageManipulation)
    {
        //
    }
    
     /**
     * Shows images by album
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function byAlbum(Album $album)
    {
        //
    }
}
