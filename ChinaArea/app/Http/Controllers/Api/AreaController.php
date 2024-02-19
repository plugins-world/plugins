<?php

namespace Plugins\ChinaArea\Http\Controllers\Api;

use Plugins\ChinaArea\Models\Area;
use Illuminate\Routing\Controller;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\MarketManager\Utils\LaravelCache;

class AreaController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $result = LaravelCache::remember('areas', now()->addDays(3), function () {
            $result = [];

            $areas = Area::getAreas();
            if (!$areas) {
                return [];
            }
    
            foreach ($areas as $key => $area) {
                $citys = Area::getAreas($area->id, 1);

                $result['provinces'][$key] = $area;
                $result['provinces'][$key]['citys'] = $citys;

                $districts = [];
                foreach ($citys as $k => $city) {
                    $districts = Area::getAreas($city->id, 2);
                    $result['provinces'][$key]['citys'][$k]['districts'] = $districts;
                }
            }

            return $result;
        });

        return $this->success($result);
    }
}
