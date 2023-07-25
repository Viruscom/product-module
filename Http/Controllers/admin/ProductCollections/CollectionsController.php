<?php

namespace Modules\Shop\Http\Controllers\admin\ProductCollections;

use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use Modules\Shop\Http\Requests\ProductCollectionStoreRequest;
use Modules\Shop\Models\Admin\ProductCollection\ProductCollection;
use Modules\Shop\Models\Admin\Products\Product;

class CollectionsController extends Controller
{
    public function index()
    {
        $collections = ProductCollection::all();

        return view('shop::admin.products.collections.index', compact('collections'));
    }

    public function create()
    {
        $products = Product::where('active', true)->whereNotNull('price')->isInStock()->get();

        return view('shop::admin.products.collections.create', compact('products'));
    }

    public function store(ProductCollectionStoreRequest $request)
    {
        if (!ProductCollection::validateUniqueMainProduct($request)) {
            return redirect()->back()->withInput()->withErrors(['Не можете да добавяте колекция към основен продукт, който вече е в активна колекция. Допуска се една активна колекция за един основен продукт.']);
        }

        $validatedProducts = ProductCollection::getValidatedAdditionalProducts($request);
        if (array_key_exists('error', $validatedProducts)) {
            return redirect()->back()->withInput()->withErrors($validatedProducts);
        }

        if (!ProductCollection::validateDiscounts($validatedProducts)) {
            return redirect()->back()->withInput()->withErrors(['Не можете да добавяте отстъпки, които са по-големи от стойността на продукта.']);
        }

        ProductCollection::store($request, $validatedProducts);

        return redirect()->route('admin.product-collections.index')->with('success-message', 'admin.common.successful_create');
    }

    public function edit($id)
    {
        $collection = ProductCollection::find($id);
        WebsiteHelper::redirectBackIfNull($collection);
        $products = Product::where('active', true)->whereNotNull('price')->where('units_in_stock', '>', 0)->get();

        return view('shop::admin.products.collections.edit', compact('collection', 'products'));
    }

    public function update($id, ProductCollectionStoreRequest $request)
    {
        $collection = ProductCollection::find($id);
        WebsiteHelper::redirectBackIfNull($collection);

        if (!ProductCollection::validateUniqueMainProduct($request, $collection->id)) {
            return redirect()->back()->withErrors(['Не можете да добавяте/редактирате колекция към основен продукт, който вече е в активна колекция. Допуска се една активна колекция за един основен продукт.']);
        }

        $validatedProducts = ProductCollection::getValidatedAdditionalProducts($request);
        if (array_key_exists('error', $validatedProducts)) {
            return redirect()->back()->withErrors($validatedProducts);
        }

        if (!ProductCollection::validateDiscounts($validatedProducts)) {
            return redirect()->back()->withErrors(['Не можете да добавяте отстъпки, които са по-големи от стойността на продукта.']);
        }

        $collection->getUpdateData($request, $validatedProducts);

        return redirect()->route('admin.product-collections.index')->with('success-message', 'admin.common.successful_edit');
    }

    public function show($id)
    {
        $collection = ProductCollection::find($id);
        WebsiteHelper::redirectBackIfNull($collection);

        return view('shop::admin.products.collections.show', compact('collection'));
    }

    public function changeActiveStatus($id, $status)
    {
        $collection = ProductCollection::find($id);
        WebsiteHelper::redirectBackIfNull($collection);

        $otherActiveCollection = ProductCollection::where('active', true)->where('id', '!=', $collection->id)->where('main_product_id', $collection->main_product_id)->first();
        if (!is_null($otherActiveCollection)) {
            return redirect()->back()->withErrors(['Не можете да активирате колекция към основен продукт, който вече е в активна колекция. Допуска се една активна колекция за един основен продукт.']);
        }

        $collection->updateAvtive($status);

        return redirect()->back();
    }
}
