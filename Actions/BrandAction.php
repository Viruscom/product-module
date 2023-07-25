<?php

namespace Modules\Shop\Actions;

class BrandAction
{
    public function logoDelete($model, $modelClass): bool
    {
        if ($model->existsFile($model->logo_filename)) {
            $model->deleteFile($model->logo_filename);
            $model->update(['logo_filename' => null]);

            $modelClass::cacheUpdate();

            return true;
        }

        return false;
    }
}
