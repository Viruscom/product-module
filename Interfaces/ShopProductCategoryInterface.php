<?php

namespace Modules\Shop\Interfaces;

use App\Actions\CommonControllerAction;
use Modules\Shop\Http\Requests\ProductCategoryStoreRequest;
use Modules\Shop\Http\Requests\ProductCategoryUpdateRequest;

interface ShopProductCategoryInterface
{
    public function index();
    public function create();
    public function store(ProductCategoryStoreRequest $request, CommonControllerAction $action);
    public function edit($id);
    public function update($id, ProductCategoryUpdateRequest $request, CommonControllerAction $action);
    public function active($id, $active);
    public function delete($id, CommonControllerAction $action);
    public function deleteImage($id, CommonControllerAction $action);
}
