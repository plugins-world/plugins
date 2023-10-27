<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

// use App\Utilities\ConfigUtility;
// use App\Utilities\SubscribeUtility;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

return new class extends Migration
{
    protected $fresnsWordBody = [
        // 'type' => SubscribeUtility::TYPE_USER_ACTIVITY,
        // 'fskey' => 'file_storage',
        // 'cmdWord' => 'stats',
    ];

    protected $fresnsConfigItems = [
        [
            'item_tag' => 'file_storage',
            'item_key' => 'file_storage_driver',
            'item_type' => 'string',
            'item_value' => 'local',
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'file_storage_timezone',
            'item_type' => 'string',
            'item_value' => 'PRC',
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'is_use_center_config',
            'item_type' => 'boolean',
            'item_value' => true,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'app_id',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'secret_id',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'secret_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'reigon',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'bucket',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'signed_url',
            'item_type' => 'boolean',
            'item_value' => true,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'use_https',
            'item_type' => 'boolean',
            'item_value' => true,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'domain',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'cdn',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'oss_root',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'oss_access_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'oss_secret_key',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'oss_endpoint',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'file_storage',
            'item_key' => 'oss_bucket',
            'item_type' => 'string',
            'item_value' => null,
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // addSubscribeItem
        // \FresnsCmdWord::plugin()->addSubscribeItem($this->fresnsWordBody);

        // addKeyValues to Config table
        ConfigUtility::addFresnsConfigItems($this->fresnsConfigItems);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // removeSubscribeItem
        // \FresnsCmdWord::plugin()->removeSubscribeItem($this->fresnsWordBody);

        // removeKeyValues from Config table
        ConfigUtility::removeFresnsConfigItems($this->fresnsConfigItems);
    }
};
