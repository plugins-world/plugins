<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

// use App\Utilities\ConfigUtility;
// use App\Utilities\SubscribeUtility;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

return new class extends Migration
{
    protected $fresnsWordBody = [
        // 'type' => SubscribeUtility::TYPE_USER_ACTIVITY,
        // 'fskey' => 'easy_map',
        // 'cmdWord' => 'stats',
    ];

    protected $fresnsConfigItems = [
        [
            'item_tag' => 'easy_map',
            'item_key' => 'map_default_platform',
            'item_type' => 'string',
            'item_value' => 'amap',
        ],
        [
            'item_tag' => 'easy_map',
            'item_key' => 'amap',
            'item_type' => 'json',
            'item_value' => [
                'request_url' => null,
                'key' => null,
            ],
        ]
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
