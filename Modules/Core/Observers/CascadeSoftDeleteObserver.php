<?php

namespace Modules\Core\Observers;

use Illuminate\Database\Eloquent\Model;

class CascadeSoftDeleteObserver
{
    public function deleting(Model $model): void
    {
        if (!property_exists($model, 'cascadeDeletes')) {
            return;
        }

        foreach ($model->cascadeDeletes as $relation) {
            $relationInstance = $model->{$relation}();

            if ($relationInstance instanceof HasOne) {
                $related = $relationInstance->first();

                if ($related) {
                    $related->delete();
                }

                continue;
            }

            foreach ($relationInstance->get() as $related) {
                $related->delete();
            }
        }
    }

    public function restoring(Model $model): void
    {
        if (request()->input('restore_cascade', 0) != 1) {
            return;
        }

        if (!property_exists($model, 'cascadeDeletes')) {
            return;
        }

        foreach ($model->cascadeDeletes as $relation) {
            $relationInstance = $model->{$relation}();

            if ($relationInstance instanceof HasOne) {
                $related = $relationInstance
                    ->onlyTrashed()
                    ->first();

                if ($related) {
                    $related->restore();
                }

                continue;
            }

            $relationInstance
                ->onlyTrashed()
                ->get()
                ->each(function ($related): void {
                    $related->restore();
                });
        }
    }
}
