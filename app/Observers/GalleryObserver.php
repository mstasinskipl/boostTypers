<?php

namespace App\Observers;

use App\Gallery;

class GalleryObserver
{
    /**
     * Handle the gallery "created" event.
     *
     * @param  \App\Gallery  $gallery
     * @return void
     */
    public function created(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the gallery "updated" event.
     *
     * @param  \App\Gallery  $gallery
     * @return void
     */
    public function updated(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the gallery "deleted" event.
     *
     * @param  \App\Gallery  $gallery
     * @return void
     */
    public function deleted(Gallery $gallery)
    {
        $gallery->images()->delete();
    }

    /**
     * Handle the gallery "restored" event.
     *
     * @param  \App\Gallery  $gallery
     * @return void
     */
    public function restored(Gallery $gallery)
    {
        //
    }

    /**
     * Handle the gallery "force deleted" event.
     *
     * @param  \App\Gallery  $gallery
     * @return void
     */
    public function forceDeleted(Gallery $gallery)
    {
        //
    }
}
