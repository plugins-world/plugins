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
        // 'fskey' => 'github_auth',
        // 'cmdWord' => 'stats',
    ];

    protected $fresnsConfigItems = [
        [
            'item_tag' => 'github_auth',
            'item_key' => 'client_id',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'github_auth',
            'item_key' => 'client_secret',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'github_auth',
            'item_key' => 'redirect',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'github_auth',
            'item_key' => 'is_enable_proxy',
            'item_type' => 'boolean',
            'item_value' => null,
        ],
        [
            'item_tag' => 'github_auth',
            'item_key' => 'proxy_http',
            'item_type' => 'string',
            'item_value' => null,
        ],
        [
            'item_tag' => 'github_auth',
            'item_key' => 'proxy_https',
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
