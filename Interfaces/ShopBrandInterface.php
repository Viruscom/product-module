<?php

namespace Modules\Shop\Interfaces;

use App\Actions\CommonControllerAction;
use Modules\Shop\Actions\BrandAction;
use Modules\Shop\Http\Requests\BrandStoreRequest;
use Modules\Shop\Http\Requests\BrandUpdateRequest;

interface ShopBrandInterface
{
    public function index();
    public function create();
    public function store(BrandStoreRequest $request, CommonControllerAction $action);
    public function edit($id);
    public function update($id, BrandUpdateRequest $request, CommonControllerAction $action);
    public function active($id, $active);
    public function delete($id, CommonControllerAction $action);
    public function deleteImage($id, CommonControllerAction $action);
    public function deleteLogo($id, CommonControllerAction $action, BrandAction $brandAction);
}
