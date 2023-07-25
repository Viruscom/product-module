<?php

namespace Modules\Shop\Http\Controllers\admin\ProductStocks;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Shop\Models\Admin\ProductAttribute\ProductAttribute;
use Modules\Shop\Models\Admin\ProductAttribute\Values\ProductAttributeValue;
use Modules\Shop\Models\Admin\ProductCategory\Category;
use Modules\Shop\Models\Admin\Products\Product;

class ProductStocksController extends Controller
{

    public function index()
    {
        $products               = Product::with('translations', 'combinations')->get();
        $productAttributes      = ProductAttribute::with('translations')->get();
        $productAttributeValues = ProductAttributeValue::with('translations')->get();
        $productCategories      = Category::with('translations')->orderBy('position')->get();

        return view('shop::admin.products.stocks.index', compact('products', 'productAttributes', 'productAttributeValues', 'productCategories'));
    }

    public function stockMovements()
    {

    }

    public function stockMovementsAdd()
    {
        $products               = Product::with('translations', 'combinations')->get();
        $productAttributes      = is_null(Cache::get('adminProductAttributes')) ? ProductAttribute::updateCache() : Cache::get('adminProductAttributes');
        $productAttributeValues = is_null(Cache::get('adminProductAttributeValues')) ? ProductAttributeValue::updateCache() : Cache::get('adminProductAttributeValues');
        $internalSuppliers      = InternalSupplier::with('defaultTranslation')->get();

        return view('shop::admin.products.stocks.movements.add', compact('products', 'productAttributes', 'productAttributeValues', 'internalSuppliers'));
    }

    public function stockMovementsRemove()
    {

    }
}
