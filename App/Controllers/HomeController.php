<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;

class HomeController extends AControllerBase
{

    public function index()
    {
        return $this->html(
            [
                'meno' => 'Patrik Hrkút'
            ]);
    }

    public function contact()
    {

        $longitude = (float)49.33333;
        $latitude = (float)18.22222;
        $radius = 10000;//rand(1,10); // in miles

        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);

        return $this->html(
            [
                'lng' => rand($lng_min * 100000, $lng_max * 100000) / 100000,
                'lat' => rand($lat_min * 100000, $lat_max * 100000) / 100000,
            ]
        );
    }
}