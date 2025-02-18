<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jevan Tang
 * Released under the Apache-2.0 License.
 */

// use App\Utilities\ConfigUtility;
// use App\Utilities\SubscribeUtility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $fresnsWordBody = [
        // 'type' => SubscribeUtility::TYPE_USER_ACTIVITY,
        // 'fskey' => 'cls_logger',
        // 'cmdWord' => 'stats',
    ];

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
        // ConfigUtility::removeFresnsConfigItems($this->fresnsConfigItems);
    }
};
