<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResizeImageRequest;
use App\Models\ImageManipulation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Models\Album;
use Intervention\Image\Facades\Image;
use App\Http\Resources\V1\ImageManipulationResource;

class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ImageManipulationResource::collection(ImageManipulation::paginate());
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
            
            copy($image, $originalPath);
        }
        $data['path'] = $dir.$data['name'];
        
        $w = $all['w'];
        $h = $all['h'] ?? false;
        
        list($width, $height, $imageResized) = $this->getImageWidthAndHeight($w, $h, $originalPath);
        
        $resizedFilename = $filename.'-resized.'.$extension;
        
        $imageResized->resize($width, $height)->save($absolutePath.$resizedFilename);
        $data['output_path'] = $dir.$resizedFilename;
        
        $imageManipulation = ImageManipulation::create($data);
        
        return new ImageManipulationResource($imageManipulation);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImageManipulation  $image
     * @return \Illuminate\Http\Response
     */
    public function show(ImageManipulation $image)
    {
        return new ImageManipulationResource($image);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImageManipulation  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageManipulation $image)
    {
        $image->delete();
        return response('', 204);
    }
    
     /**
     * Shows images by album
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function byAlbum(Album $album)
    {
        $where = [
            'album_id' => $album->id
        ];
        return ImageManipulationResource::collection(ImageManipulation::where($where)->paginate());
    }
    
    protected function getImageWidthAndHeight($w, $h, string $originalPath)
    {
       $image = Image::make($originalPath);
       $originalWidth = $image->width();
       $originalHeight = $image->height();
       
       if (str_ends_with($w, '%')) {
          $ratioW = (float)str_replace('%', '', $w);
          $ratioH = $h ? (float)str_replace('%', '', $h) : $ratioW;
          
          $newWidth = $originalWidth * $ratioW / 100;
          $newHeight = $originalHeight * $ratioH / 100;
       } else {
           //in case of $w is not percentage
           $newWidth = (float)$w;
           
           $newHeight = $h ? (float) $h : $originalHeight * $newWidth/$originalWidth;
       }
       
       return [$newWidth, $newHeight, $image];
    }
}
