<?php

namespace Modules\Product\Http\Controllers\Front;

use App\Helpers\CacheKeysHelper;
use App\Helpers\ModuleHelper;
use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use App\Models\Settings\Application;
use App\Models\SpecialPage\SpecialPage;
use App\Models\SpecialPage\SpecialPageTranslation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Modules\Product\Models\Admin\Brands\Brand;
use Modules\Product\Models\Admin\Brands\BrandTranslation;
use Modules\Product\Models\Admin\ProductCategory\Category;
use Modules\Product\Models\Admin\ProductCategory\CategoryTranslation;
use Modules\Product\Models\Admin\Products\Product;
use Modules\Product\Models\Admin\Products\ProductTranslation;

class FrontProductModuleController extends Controller
{
    public function makeInquiry($languageSlug, $productId, Request $request)
    {
        $product = Product::where('id', $productId)->with('translations')->first();
        WebsiteHelper::redirectBackIfNull($product);

        $specialPage                   = SpecialPage::where('type', SpecialPage::TYPE_CONTACTS_PAGE)->first();
        $viewArray['currentModel']     = SpecialPageTranslation::where('special_page_id', $specialPage->id)->with('parent')->first();
        $viewArray['product']          = $product;
        $viewArray['recaptchaSiteKey'] = Application::getSettings()->google_recaptcha_ver2;
        $viewArray['googleMapsUrl']    = Application::getSettings()->google_maps_iframe;

        if (array_key_exists('RetailObjects', ModuleHelper::getActiveModules())) {
            $viewArray['retailObjects'] = Cache::get(CacheKeysHelper::$RETAIL_OBJECT_FRONT);
        }

        return redirect()->to($languageSlug . '/' . $viewArray['currentModel']->url)->with(['viewArray' => $viewArray]);
    }

    public function loadBrandPage($languageSlug, $slug)
    {
        $viewArray['currentModel'] = BrandTranslation::where('url', 'brand/' . $slug)->with('parent')->first();
        WebsiteHelper::abortIfNull($viewArray['currentModel']);

        WebsiteHelper::loadCollectionsForModules($viewArray['currentModel'], $viewArray['currentModel']->parent);
        $eagerLoadRelations = [];
        WebsiteHelper::loadGalleryRelations($viewArray['currentModel']->parent, $eagerLoadRelations);


        return view('product::front.brands.show', ['viewArray' => $viewArray]);
    }

    public function loadCategoryPage($languageSlug, $slug)
    {
        $viewArray['currentModel'] = CategoryTranslation::where('url', 'category/' . $slug)->with('parent')->first();
        WebsiteHelper::abortIfNull($viewArray['currentModel']);

        WebsiteHelper::loadCollectionsForModules($viewArray['currentModel'], $viewArray['currentModel']->parent);
        $eagerLoadRelations = [];
        WebsiteHelper::loadGalleryRelations($viewArray['currentModel']->parent, $eagerLoadRelations);

        return view('product::front.categories.show', ['viewArray' => $viewArray]);
    }

    public function loadProductPage($languageSlug, $slug)
    {
        $viewArray['currentModel'] = ProductTranslation::where('url', 'product/' . $slug)->with('parent')->first();
        WebsiteHelper::abortIfNull($viewArray['currentModel']);

        WebsiteHelper::loadCollectionsForModules($viewArray['currentModel'], $viewArray['currentModel']->parent);
        $eagerLoadRelations = [];
        WebsiteHelper::loadGalleryRelations($viewArray['currentModel']->parent, $eagerLoadRelations);

        $viewArray['canonicalTagUrl'] = $viewArray['currentModel']->parent->getUrl($languageSlug);
        if (!is_null($viewArray['currentModel']->parent->main_product_id)) {
            $mainProduct = Product::where('id', $viewArray['currentModel']->parent->main_product_id)->first();
            if (!is_null($mainProduct)) {
                $viewArray['canonicalTagUrl'] = $mainProduct->getUrl($languageSlug);
            }
        }
        return view('product::front.products.show', ['viewArray' => $viewArray]);
    }
}
