<?php

namespace App\Http\Controllers;

use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class WilayahController extends Controller
{
    // KOTA
    public function cities($provinceCode)
    {
        return City::where(
            'province_code',
            $provinceCode
        )->get();
    }

    // KECAMATAN
    public function districts($cityCode)
    {
        return District::where(
            'city_code',
            $cityCode
        )->get();
    }

    // DESA
    public function villages($districtCode)
    {
        return Village::where(
            'district_code',
            $districtCode
        )->get();
    }
}