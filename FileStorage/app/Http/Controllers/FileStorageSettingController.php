<?php

namespace Plugins\FileStorage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Plugins\FileStorage\Utilities\OssUtility;
use Plugins\LaravelConfig\Models\Config;
use Plugins\LaravelConfig\Utilities\ConfigUtility;
use Plugins\FileStorage\Utilities\CosUtility;

class FileStorageSettingController extends Controller
{
    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('FileStorage::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        $cosItemKeys = [
            'file_storage_timezone',
            'is_use_center_config',
            'app_id',
            'secret_id',
            'secret_key',
            'reigon',
            'bucket',
            'signed_url',
            'use_https',
            'domain',
            'cdn',
        ];

        $ossItemKeys = [
            'is_use_center_config',
            'oss_root',
            'oss_access_key',
            'oss_secret_key',
            'oss_endpoint',
            'oss_bucket',
        ];

        $file_storage_driver = Config::getValueByKey('file_storage_driver');
        $cosConfigs = Config::whereIn('item_key', $cosItemKeys)->where('item_tag', 'file_storage')->get();
        $ossConfigs = Config::whereIn('item_key', $ossItemKeys)->where('item_tag', 'file_storage')->get();

        return view('FileStorage::setting', [
            'file_storage_driver' => $file_storage_driver,
            'cosConfigs' => $cosConfigs,
            'ossConfigs' => $ossConfigs,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'file_storage_driver' => 'required|string',
            'file_storage_timezone' => 'nullable|string',
            'is_use_center_config' => 'nullable|boolean:0,1',
            'app_id' => 'nullable|string',
            'secret_id' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'reigon' => 'nullable|string',
            'bucket' => 'nullable|string',
            'signed_url' => 'nullable|boolean:0,1',
            'use_https' => 'nullable|boolean:0,1',
            'domain' => 'nullable|string',
            'cdn' => 'nullable|string',
            'oss_root' => 'nullable|string',
            'oss_access_key' => 'nullable|string',
            'oss_secret_key' => 'nullable|string',
            'oss_endpoint' => 'nullable|string',
            'oss_bucket' => 'nullable|string',
        ]);

        $bucket = CosUtility::cleanBucketName(\request('bucket'), \request('app_id'));
        $host = CosUtility::cleanHost(\request('domain'));
        $ossBucket = OssUtility::cleanBucketName(\request('oss_bucket'), \request('oss_access_key'));
        $file_storage_timezone = \request('file_storage_timezone', 'PRC');

        \request()->offsetSet('file_storage_timezone', $file_storage_timezone);
        \request()->offsetSet('bucket', $bucket);
        \request()->offsetSet('domain', $host);
        \request()->offsetSet('oss_bucket', $ossBucket);

        $itemKeys = [
            'file_storage_driver',
            'file_storage_timezone',
            'is_use_center_config',
            'app_id',
            'secret_id',
            'secret_key',
            'reigon',
            'bucket',
            'signed_url',
            'use_https',
            'domain',
            'cdn',
            'oss_root',
            'oss_access_key',
            'oss_secret_key',
            'oss_endpoint',
            'oss_bucket',
            'oss_domain',
        ];

        // Config updateConfigs with $itemKeys and 'file_storage'
        ConfigUtility::updateConfigs($itemKeys, 'file_storage');

        return redirect(route('file-storage.setting'));
    }
}
