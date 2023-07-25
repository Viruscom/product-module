<?php

namespace Modules\Shop\Http\Controllers\admin\RegisteredUsers;

use App\Helpers\WebsiteHelper;
use App\Http\Controllers\Controller;
use Modules\Shop\Entities\RegisteredUser\ShopRegisteredUser;

class ShopAdminRegisteredUserFavProductsController extends Controller
{
    public function index($id)
    {
        $registeredUser = ShopRegisteredUser::where('id', $id)->with('favoriteProducts', 'favoriteProducts.product')->first();
        WebsiteHelper::redirectBackIfNull($registeredUser);

        return view('shop::admin.registered_users.favorite_products.index', ['registeredUser' => $registeredUser, 'favoriteProducts' => $registeredUser->favoriteProducts]);
    }
}
