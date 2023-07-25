<?php

namespace Modules\Shop\Interfaces;

use App\Actions\CommonControllerAction;
use Modules\Shop\Http\Requests\ProductAttributeStoreRequest;
use Modules\Shop\Http\Requests\ProductAttributeUpdateRequest;

interface ShopProductAttributeInterface
{
    public function index();
    public function create();
    public function store(ProductAttributeStoreRequest $request, CommonControllerAction $action);
    public function edit($id);
    public function update($id, ProductAttributeUpdateRequest $request, CommonControllerAction $action);
    public function delete($id, CommonControllerAction $action);
}
