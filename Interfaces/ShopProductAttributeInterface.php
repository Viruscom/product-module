<?php

namespace Modules\Product\Interfaces;

use App\Actions\CommonControllerAction;
use Modules\Product\Http\Requests\ProductAttributeStoreRequest;
use Modules\Product\Http\Requests\ProductAttributeUpdateRequest;

interface ShopProductAttributeInterface
{
    public function index();
    public function create();
    public function store(ProductAttributeStoreRequest $request, CommonControllerAction $action);
    public function edit($id);
    public function update($id, ProductAttributeUpdateRequest $request, CommonControllerAction $action);
    public function delete($id, CommonControllerAction $action);
}
