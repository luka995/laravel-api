<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageManipulation extends Model
{
    use HasFactory;
    
    const TYPE_RESIZE = 'resize';
    
    //not using in database ORM will ignore updated_at while inserting data
    const UPDATED_AT = false;
    
    protected $filable = ['name', 'path', 'type', 'data', 'output_path', 'user_id', 'album_id'];
}
