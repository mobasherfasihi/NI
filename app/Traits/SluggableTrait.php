<?php
namespace App\Traits;

use App\Observers\SluggableTraitObserver;

/**
 * Class sluggable
 */
trait SluggableTrait
{
    /**
     * Hook into the Eloquent model events to create or
     * update the slug as required.
     */
    public static function bootSluggableTrait()
    {
        static::observe(app(SluggableTraitObserver::class));
    }
}