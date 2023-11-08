<?php

namespace Plugins\EasyMap\Utilities;

class AMapApiUtility
{
    public static function getGeoCode(string $address)
    {
        $method = 'GET';
        $action = '/v3/geocode/geo';

        $resp = MapUtility::request($method, $action, [
            'address' => $address
        ]);

        $result = $resp['geocodes'][0] ?? null;
        $location = $result['location'];

        $locationArr = explode(',', $location);
        list($longitude, $latitude) = $locationArr;

        $result['longitude'] = $longitude;
        $result['latitude'] = $latitude;
        $result['longitude_desc'] = '经度: '.$longitude;
        $result['latitude_desc'] = '纬度: '.$latitude;

        return $result;
    }
}
