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
    use Modules\Product\Models\Admin\Products\Product;

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
    }
