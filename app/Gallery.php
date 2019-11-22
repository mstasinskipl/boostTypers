<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
