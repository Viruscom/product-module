<?php

namespace Modules\Shop\Models;

use App\Helpers\AdminHelper;
use Illuminate\Database\Eloquent\Model;
use Modules\Shop\Models\Admin\Brands\Brand;
use Modules\Shop\Models\Admin\ProductCategory\Category;
use Modules\Shop\Models\Admin\Products\Product;

class Shop extends Model
{
    public static function allocateModule($viewArray)
    {
        switch (class_basename($viewArray['currentModel']->parent)) {
            case 'Product':
                return view('shop::front.products.show', ['viewArray' => $viewArray]);
            case 'Brand':
                return view('shop::front.brands.show', ['viewArray' => $viewArray]);
            case 'Category':
                return view('shop::front.categories.show', ['viewArray' => $viewArray]);
            default:
                abort(404);
        }
    }

    public function setKeys($array): array
    {
        $array[1]['sys_image_name'] = trans('shop::admin.product_brands.index');
        $array[1]['sys_image']      = Brand::$BRAND_SYSTEM_IMAGE;
        $array[1]['sys_image_path'] = AdminHelper::getSystemImage(Brand::$BRAND_SYSTEM_IMAGE);
        $array[1]['ratio']          = Brand::$BRAND_RATIO;
        $array[1]['mimes']          = Brand::$BRAND_MIMES;
        $array[1]['max_file_size']  = Brand::$BRAND_MAX_FILE_SIZE;
        $array[1]['file_rules']     = 'mimes:' . Brand::$BRAND_MIMES . '|size:' . Brand::$BRAND_MAX_FILE_SIZE . '|dimensions:ratio=' . Brand::$BRAND_RATIO;

        $array[2]['sys_image_name'] = trans('shop::admin.product_categories.index');
        $array[2]['sys_image']      = Category::$PRODUCT_CATEGORY_SYSTEM_IMAGE;
        $array[2]['sys_image_path'] = AdminHelper::getSystemImage(Category::$PRODUCT_CATEGORY_SYSTEM_IMAGE);
        $array[2]['ratio']          = Category::$PRODUCT_CATEGORY_RATIO;
        $array[2]['mimes']          = Category::$PRODUCT_CATEGORY_MIMES;
        $array[2]['max_file_size']  = Category::$PRODUCT_CATEGORY_MAX_FILE_SIZE;
        $array[2]['file_rules']     = 'mimes:' . Category::$PRODUCT_CATEGORY_MIMES . '|size:' . Category::$PRODUCT_CATEGORY_MAX_FILE_SIZE . '|dimensions:ratio=' . Category::$PRODUCT_CATEGORY_RATIO;

        $array[3]['sys_image_name'] = trans('shop::admin.products.index');
        $array[3]['sys_image']      = Product::$PRODUCT_SYSTEM_IMAGE;
        $array[3]['sys_image_path'] = AdminHelper::getSystemImage(Product::$PRODUCT_SYSTEM_IMAGE);
        $array[3]['ratio']          = Product::$PRODUCT_RATIO;
        $array[3]['mimes']          = Product::$PRODUCT_MIMES;
        $array[3]['max_file_size']  = Product::$PRODUCT_MAX_FILE_SIZE;
        $array[3]['file_rules']     = 'mimes:' . Product::$PRODUCT_MIMES . '|size:' . Product::$PRODUCT_MAX_FILE_SIZE . '|dimensions:ratio=' . Product::$PRODUCT_RATIO;

        return $array;
    }
}
