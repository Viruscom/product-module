<?php

namespace Modules\Product\Interfaces;

use App\Actions\CommonControllerAction;
use Modules\Product\Actions\ProductAction;
use Modules\Product\Http\Requests\ProductStoreRequest;
use Modules\Product\Http\Requests\ProductUpdateRequest;

interface ShopProductInterface
{
    public function index();
    public function create($category_id, ProductAction $action);
    public function store(ProductStoreRequest $request, CommonControllerAction $action, ProductAction $productAction);
    public function edit($id, ProductAction $action);
    public function update($id, ProductUpdateRequest $request, CommonControllerAction $action, ProductAction $productAction);
    public function active($id, $active);
    public function delete($id, CommonControllerAction $action);
    public function deleteImage($id, CommonControllerAction $action);
    public function makeProductAdBox($id, ProductAction $action);
}
