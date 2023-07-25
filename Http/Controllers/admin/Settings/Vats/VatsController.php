<?php

namespace Modules\Shop\Http\Controllers\admin\Settings\Vats;

use App\Http\Controllers\Controller;
use Modules\Shop\Entities\Settings\City;
use Modules\Shop\Entities\Settings\CityVatCategory;
use Modules\Shop\Entities\Settings\Country;
use Modules\Shop\Entities\Settings\State;
use Modules\Shop\Entities\Settings\StateVatCategory;
use Modules\Shop\Entities\Settings\VatCategory;
use Modules\Shop\Http\Requests\CityVatCategoryStoreRequest;
use Modules\Shop\Http\Requests\CityVatCategoryUpdateRequest;
use Modules\Shop\Http\Requests\StateVatCategoryStoreRequest;
use Modules\Shop\Http\Requests\StateVatCategoryUpdateRequest;
use Modules\Shop\Http\Requests\VatCategoryStoreRequest;
use Modules\Shop\Http\Requests\VatCategoryUpdateRequest;
use Modules\Shop\Http\Requests\VatRequest;

class VatsController extends Controller
{
    //----countries defualt vats
    public function index()
    {
        $countries = Country::orderBy('name', 'asc')->paginate(20);

        return view('shop::vats.index', ['countries' => $countries]);
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);

        return view('shop::vats.edit', ['country' => $country]);
    }
    public function states($id)
    {
        $country = Country::findOrFail($id);

        return view('shop::vats.states.index', ['country' => $country]);
    }

    //----states defualt vats
    public function statesEdit($id)
    {
        $state = State::findOrFail($id);

        return view('shop::vats.states.edit', ['state' => $state]);
    }
    public function statesUpdate($id, VatRequest $request)
    {
        $state = State::findOrFail($id);
        $state->update(['vat' => $request->vat]);

        return redirect(route('vats.countries.states.index', ['id' => $state->country->id]))->with('success', __('Successful update'));
    }
    public function update($id, VatRequest $request)
    {
        $country = Country::findOrFail($id);
        if (is_null($request->vat)) {
            $request->vat = 0;
        }
        $country->update(['vat' => $request->vat]);

        return redirect(route('vats.countries.index'))->with('success', __('Successful update'));
    }
    //----cities defualt vats
    public function cities($id)
    {
        $state = State::findOrFail($id);

        return view('shop::vats.states.cities.index', ['state' => $state]);
    }

    public function citiesEdit($id)
    {
        $city = City::findOrFail($id);

        return view('shop::vats.states.cities.edit', ['city' => $city]);
    }

    public function citiesUpdate($id, VatRequest $request)
    {
        $city = City::findOrFail($id);
        $city->update(['vat' => $request->vat]);

        return redirect(route('vats.countries.states.cities.index', ['id' => $city->state->id]))->with('success', __('Successful update'));
    }
    //----countries categories
    public function categories($id)
    {
        $country = Country::findOrFail($id);

        return view('shop::vats.categories.index', ['country' => $country]);
    }

    public function categoriesCreate($id)
    {
        $country = Country::findOrFail($id);

        return view('shop::vats.categories.create', ['country' => $country]);
    }


    public function categoriesStore($id, VatCategoryStoreRequest $request)
    {
        $country  = Country::findOrFail($id);
        $category = $country->vat_categories()->create(['name' => $request->name, 'vat' => $request->vat]);

        return redirect(route('vats.countries.categories.index', ['id' => $country->id]))->with('success', __('Successful store'));
    }

    public function categoriesEdit($id)
    {
        $category = VatCategory::findOrFail($id);

        return view('shop::vats.categories.edit', ['category' => $category]);
    }

    public function categoriesUpdate($id, VatCategoryUpdateRequest $request)
    {
        $category = VatCategory::findOrFail($id);
        $category->update(['name' => $request->name, 'vat' => $request->vat]);

        return redirect(route('vats.countries.categories.index', ['id' => $category->country->id]))->with('success', __('Successful update'));
    }

    public function categoriesDestroy($id)
    {
        $category = VatCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', __('Successful update'));
    }
    //----states categories
    public function statesCategories($id)
    {
        $state = State::findOrFail($id);

        return view('shop::vats.states.categories.index', ['state' => $state]);
    }

    public function statesCategoriesCreate($id)
    {
        $state = State::findOrFail($id);

        return view('shop::vats.states.categories.create', ['state' => $state]);
    }


    public function statesCategoriesStore($id, StateVatCategoryStoreRequest $request)
    {
        $state    = State::findOrFail($id);
        $category = $state->vat_categories()->create(['vat_category_id' => $request->vat_category_id, 'vat' => $request->vat]);

        return redirect(route('vats.countries.states.categories.index', ['id' => $state->id]))->with('success', __('Successful store'));
    }

    public function statesCategoriesEdit($id)
    {
        $category = StateVatCategory::findOrFail($id);

        return view('shop::vats.states.categories.edit', ['category' => $category]);
    }

    public function statesCategoriesUpdate($id, StateVatCategoryUpdateRequest $request)
    {
        $category = StateVatCategory::findOrFail($id);
        $category->update(['vat' => $request->vat]);

        return redirect(route('vats.countries.states.categories.index', ['id' => $category->state->id]))->with('success', __('Successful update'));
    }

    public function statesCategoriesDestroy($id)
    {
        $category = StateVatCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', __('Successful update'));
    }
    //----cities categories
    public function citiesCategories($id)
    {
        $city = City::findOrFail($id);

        return view('shop::vats.states.cities.categories.index', ['city' => $city]);
    }

    public function citiesCategoriesCreate($id)
    {
        $city = City::findOrFail($id);

        return view('shop::vats.states.cities.categories.create', ['city' => $city]);
    }


    public function citiesCategoriesStore($id, CityVatCategoryStoreRequest $request)
    {
        $city     = City::findOrFail($id);
        $category = $city->vat_categories()->create(['vat_category_id' => $request->vat_category_id, 'vat' => $request->vat]);

        return redirect(route('vats.countries.states.cities.categories.index', ['id' => $city->id]))->with('success', __('Successful store'));
    }

    public function citiesCategoriesEdit($id)
    {
        $category = CityVatCategory::findOrFail($id);

        return view('shop::vats.states.cities.categories.edit', ['category' => $category]);
    }

    public function citiesCategoriesUpdate($id, CityVatCategoryUpdateRequest $request)
    {
        $category = CityVatCategory::findOrFail($id);
        $category->update(['vat' => $request->vat]);

        return redirect(route('vats.countries.states.cities.categories.index', ['id' => $category->city->id]))->with('success', __('Successful update'));
    }

    public function citiesCategoriesDestroy($id)
    {
        $category = CityVatCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', __('Successful update'));
    }
}
