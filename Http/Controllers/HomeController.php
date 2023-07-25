<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Product\Entities\Settings\City;
use Modules\Product\Entities\Settings\Country;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'setUserLocation']);
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function setUserLocation(Request $request)
    {
        $zipCodeToSet   = null;
        $cityIdToSet    = null;
        $countryIdToSet = null;

        $cityId = $request->city_id;
        $city   = City::find($cityId);
        if (!is_null($city)) {
            $zipCodeToSet   = null;
            $cityIdToSet    = $city->id;
            $countryIdToSet = $city->country->id;
        } else {
            $countryId = $request->country_id;
            $country   = Country::find($countryId);
            if (!is_null($country)) {
                $zipCodeToSet   = null;
                $cityIdToSet    = null;
                $countryIdToSet = $country->id;
            }
        }

        if (!is_null($request->zip_code)) {
            if (!is_null($cityIdToSet)) {
                if (in_array($request->zip_code, explode(",", $city->zip_codes))) {
                    $zipCodeToSet = $request->zip_code;
                }
            } else {
                $zipCodeToSet = $request->zip_code;
                if (!is_null($countryIdToSet)) {
                    $city = DB::table('cities')->where('country_id', $countryIdToSet)->whereRaw('find_in_set(' . $request->zip_code . ',zip_codes) <> 0')->get()->first();
                    if (!is_null($city)) {
                        $cityIdToSet = $city->id;
                    }
                }
            }
        }

        if (Auth::check()) {
            //save it in some user_settings table;
            //Auth::user()->system_settings->update(['key' => 'zip_code', 'value' => $zipCodeToSet]);
            //Auth::user()->system_settings->update(['key' => 'city_id', 'value' => $cityIdToSet]);
            //Auth::user()->system_settings->update(['key' => 'country_id', 'value' => $countryIdToSet]);
        }

        session()->put("zip_code", $zipCodeToSet);
        session()->put("city_id", $cityIdToSet);
        session()->put("country_id", $countryIdToSet);

        return redirect()->back()->withInput()->with('success', __('Successful update'));
    }
}
