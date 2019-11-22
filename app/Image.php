<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $guarded = [];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getGalleryLink()
    {
        return storage_path('gallery').'/'.$this->gallery()->first()->name.'/'.$this->attributes['name'];
    }
}
