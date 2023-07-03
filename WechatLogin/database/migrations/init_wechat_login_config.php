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
        // 'fskey' => 'wechat_login',
        // 'cmdWord' => 'stats',
    ];

    protected $fresnsConfigItems = [
        [
            'item_tag' => 'wechat_login',
            'item_key' => 'wechat_login_official_account',
            'item_type' => 'object',
            'item_value' => null,
        ],
        [
            'item_tag' => 'wechat_login',
            'item_key' => 'wechat_login_mini_program',
            'item_type' => 'object',
            'item_value' => null,
        ],
        [
            'item_tag' => 'wechat_login',
            'item_key' => 'wechat_login_open_platform',
            'item_type' => 'object',
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
