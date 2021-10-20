<?php

namespace App\Observers;

use App\Actions\SlugAction;
use Illuminate\Database\Eloquent\Model;

class SluggableTraitObserver
{

     /**
     * SluggableTraitObserver constructor.
     *
     * @param App\Actions\SlugAction $slugAction
     */
    public function __construct(private SlugAction $slugAction) {}

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return boolean|null
     */
    public function saving(Model $model): bool|null
    {
        return $this->generateSlug($model);
    }

     /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * 
     * @return boolean|void
     */
    protected function generateSlug(Model $model): bool|void
    {
        $this->slugAction->slug($model);
    }
}
