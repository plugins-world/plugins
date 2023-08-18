<?php

namespace Plugins\FileStorage\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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

        $itemKeys = [
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

        $file_storage_driver = Config::getValueByKey('file_storage_driver');
        $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'file_storage')->get();

        return view('FileStorage::setting', [
            'file_storage_driver' => $file_storage_driver,
            'configs' => $configs,
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
        ]);

        $bucket = CosUtility::cleanBucketName(\request('bucket'), \request('app_id'));
        $host = CosUtility::cleanHost(\request('domain'));
        $file_storage_timezone = \request('file_storage_timezone', 'PRC');

        \request()->offsetSet('file_storage_timezone', $file_storage_timezone);
        \request()->offsetSet('bucket', $bucket);
        \request()->offsetSet('domain', $host);

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
        ];

        // Config updateConfigs with $itemKeys and 'file_storage'
        ConfigUtility::updateConfigs($itemKeys, 'file_storage');

        return redirect(route('file-storage.setting'));
    }
}
