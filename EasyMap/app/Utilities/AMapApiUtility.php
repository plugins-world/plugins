<?php

namespace Plugins\EasyMap\Utilities;

class AMapApiUtility
{
    public static function getGeoCodeGeoInfo(string $address)
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
    public static function getGeoCodeRegeoInfo(string $longitude, string $latitude, ?string $user_address = null)
    {
        $method = 'GET';
        $action = '/v3/geocode/regeo';

        // 经度,纬度
        $location = sprintf('%s,%s', $longitude, $latitude);

        $resp = MapUtility::request($method, $action, [
            'location' => $location,
        ]);

        $result = $resp['regeocode'] ?? null;
        if (!$result) {
            return null;
        }

        $addressComponent = $result['addressComponent'];
        $formatted_address = $result['formatted_address'];

        $result['country'] = $addressComponent['country'];
        $result['province'] = $addressComponent['province'];
        $result['city'] = $addressComponent['city'];
        $result['district'] = $addressComponent['district'];
        $result['township'] = $addressComponent['township'];
        $result['user_address'] = $user_address;
        $result['amap_address'] = $formatted_address;
        $result['result_address'] = empty($user_address) ? $formatted_address : $user_address;
        $result['longitude'] = $longitude;
        $result['latitude'] = $latitude;
        $result['longitude_desc'] = '经度: '.$longitude;
        $result['latitude_desc'] = '纬度: '.$latitude;

        return $result;
    }
}
