<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageManipulation extends Model
{
    use HasFactory;
    
    const TYPE_RESIZE = 'resize';
    
    //not using in database ORM will ignore updated_at while inserting data.
    //can accept only integer or string, so instead of false i set it to null
    const UPDATED_AT = null;
    
    protected $filable = ['name', 'path', 'type', 'data', 'output_path', 'user_id', 'album_id'];
}
