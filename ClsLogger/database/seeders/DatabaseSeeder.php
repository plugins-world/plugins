<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\ClsLogger\Database\Seeders;

use Illuminate\Database\Seeder;
use Plugins\LaravelConfig\Helpers\ConfigHelper;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

class DatabaseSeeder extends Seeder
{
    protected $fresnsConfigItems = [
        [
            'item_tag' => 'cls_logger',
            'item_key' => 'endpoint',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'cls_logger',
            'item_key' => 'access_key_id',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'cls_logger',
            'item_key' => 'access_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'cls_logger',
            'item_key' => 'topic_id',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'cls_logger',
            'item_key' => 'token',
            'item_type' => 'string',
            'item_value' => null,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Model::unguard();
        // $this->call("OthersTableSeeder");

        $endpoint = 'ap-hongkong.cls.tencentyun.com';
        if (app()->environment('local')) {
            $endpoint = 'ap-hongkong.cls.tencentcs.com';
        }
        $this->fresnsConfigItems[0]['item_value'] = $endpoint;
        $this->fresnsConfigItems[1]['item_value'] = '';
        $this->fresnsConfigItems[2]['item_value'] = '';
        $this->fresnsConfigItems[3]['item_value'] = '';

        ConfigUtility::addFresnsConfigItems($this->fresnsConfigItems);
        $currentConfig = ConfigHelper::fresnsConfigByItemKeys([
            'endpoint',
            'access_key_id',
            'access_key',
            'topic_id',
            'token',
        ], 'cls_logger');
        dump($currentConfig);
    }
}
