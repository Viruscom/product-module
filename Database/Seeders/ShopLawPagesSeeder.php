<?php

namespace Modules\Shop\Database\Seeders;

use App\Actions\CommonControllerAction;
use App\Helpers\LanguageHelper;
use App\Models\LawPages\LawPage;
use App\Models\LawPages\LawPageTranslation;
use Illuminate\Database\Seeder;

class ShopLawPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(CommonControllerAction $action)
    {
        $activeLanguages     = LanguageHelper::getActiveLanguages();
        $lastLawPagePosition = !is_null(LawPage::latest()->first()) ? LawPage::latest()->first()->position : 0;

        $faqPage             = LawPage::create(['position' => $lastLawPagePosition + 1, 'active' => true]);
        $deliveryMethodsPage = LawPage::create(['position' => $lastLawPagePosition + 2, 'active' => true]);
        $paymentMethodsPage  = LawPage::create(['position' => $lastLawPagePosition + 3, 'active' => true]);
        $productReturnPage   = LawPage::create(['position' => $lastLawPagePosition + 4, 'active' => true]);
        $whyToRegisterPage   = LawPage::create(['position' => $lastLawPagePosition + 5, 'active' => true]);

        foreach ($activeLanguages as $language) {
            LawPageTranslation::create([
                                           'law_page_id' => $faqPage->id,
                                           'locale'      => $language->code,
                                           'title'       => 'Често задавани въпроси',
                                           'url'         => url('/'),
                                           'description' => '',
                                       ]);
            LawPageTranslation::create([
                                           'law_page_id' => $deliveryMethodsPage->id,
                                           'locale'      => $language->code,
                                           'title'       => 'Методи на доставка',
                                           'url'         => url('/'),
                                           'description' => '',
                                       ]);
            LawPageTranslation::create([
                                           'law_page_id' => $paymentMethodsPage->id,
                                           'locale'      => $language->code,
                                           'title'       => 'Методи на плащане',
                                           'url'         => url('/'),
                                           'description' => '',
                                       ]);
            LawPageTranslation::create([
                                           'law_page_id' => $productReturnPage->id,
                                           'locale'      => $language->code,
                                           'title'       => 'Връщане на продукт',
                                           'url'         => url('/'),
                                           'description' => '',
                                       ]);
            LawPageTranslation::create([
                                           'law_page_id' => $whyToRegisterPage->id,
                                           'locale'      => $language->code,
                                           'title'       => 'Защо да се регистрирам?',
                                           'url'         => url('/'),
                                           'description' => '',
                                       ]);
        }

        $action->updateUrlCache($faqPage, LawPageTranslation::class);
        $action->updateUrlCache($deliveryMethodsPage, LawPageTranslation::class);
        $action->updateUrlCache($paymentMethodsPage, LawPageTranslation::class);
        $action->updateUrlCache($productReturnPage, LawPageTranslation::class);
        $action->updateUrlCache($whyToRegisterPage, LawPageTranslation::class);
        LawPage::cacheUpdate();
    }
}
