<?php

namespace Modules\Shop\Models\Admin\Products\Stocks;

use App\Traits\Scopes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class InternalSupplier extends Model implements TranslatableContract
{
    use Translatable, Scopes;

    public array $translatedAttributes = ['title', 'registration_address'];
    protected    $table                = "internal_suppliers";
    protected    $guarded              = ['id'];
    public static function generatePosition($request)
    {
        $internalSuppliers = self::orderBy('position', 'desc')->get();
        if (count($internalSuppliers) < 1) {
            return 1;
        }
        if (!$request->has('position') || is_null($request['position'])) {
            return $internalSuppliers->first()->position + 1;
        }

        if ($request['position'] > $internalSuppliers->first()->position) {
            return $internalSuppliers->first()->position + 1;
        }

        $internalSuppliersUpdate = self::where('position', '>=', $request['position'])->get();
        foreach ($internalSuppliersUpdate as $productUpdate) {
            $productUpdate->update(['position' => $productUpdate->position + 1]);
        }

        return $request['position'];
    }
    public static function getRequestData($request): array
    {
        $data = [
            'eik'        => $request->eik,
            'vat_number' => $request->vat_number,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'position'   => $request->position,
        ];

        $data['active'] = false;
        if ($request->has('active')) {
            $data['active'] = filter_var($request->active, FILTER_VALIDATE_BOOLEAN);
        }

        return $data;
    }
    public static function getLangArraysOnStore($data, $request, $languages, $modelId, $isUpdate)
    {
        foreach ($languages as $language) {
            $data[$language->code] = InternalSupplierTranslation::getLanguageArray($language, $request, $modelId, $isUpdate);
        }

        return $data;
    }
    public static function cacheUpdate()
    {

    }
    public function scopeArchived($query, $bool)
    {
        return $query->where('archived', $bool);
    }
    public function scopeActive($query, $active)
    {
        return $query->where('active', $active);
    }
    public function updatedPosition($request)
    {
        if (!$request->has('position') || is_null($request->position)) {
            return $this->position;
        }

        $internalSuppliers = self::orderBy('position', 'desc')->get();

        if ($request['position'] > $internalSuppliers->first()->position) {
            $request['position'] = $internalSuppliers->first()->position;
        } elseif ($request['position'] < $internalSuppliers->last()->position) {
            $request['position'] = $internalSuppliers->last()->position;
        }

        if ($request['position'] >= $this->position) {
            $internalSuppliersToUpdate = self::where('id', '<>', $this->id)->where('position', '>', $this->position)->where('position', '<=', $request['position'])->get();
            foreach ($internalSuppliersToUpdate as $productToUpdate) {
                $productToUpdate->update(['position' => $productToUpdate->position - 1]);
            }
        } else {
            $internalSuppliersToUpdate = self::where('id', '<>', $this->id)->where('position', '<', $this->position)->where('position', '>=', $request['position'])->get();
            foreach ($internalSuppliersToUpdate as $productToUpdate) {
                $productToUpdate->update(['position' => $productToUpdate->position + 1]);
            }
        }

        return $request['position'];
    }
}
