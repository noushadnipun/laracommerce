<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $table ="medias";
    protected $fillable = [
        'user_id',
        'original_name',
        'filename',
        'file_type',
        'file_size',
        'file_extension',
        'file_directory'
    ];


    public function getFileName($media_id){
        $media = Media::where('id', $media_id)->first();
        return $media->filename;
    }

    public static function fileLocation($media_id){
        $placeholder = asset('public/frontend/images/no-images.svg');
        if(empty($media_id)){
            return $placeholder;
        }
        $media = Media::where('id', $media_id)->first();
        $filename = $media?->filename;
        if(empty($filename)){
            return $placeholder;
        }
        return asset('public/uploads/images/').'/'.$filename;
    }
}
