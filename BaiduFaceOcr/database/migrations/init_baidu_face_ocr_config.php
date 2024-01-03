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
        // 'fskey' => 'baidu_face_ocr',
        // 'cmdWord' => 'stats',
    ];

    protected $fresnsConfigItems = [
        [
            'item_tag' => 'baidu_face_ocr',
            'item_key' => 'face_ocr_config',
            'item_type' => 'json',
            'item_value' => [
                'request_url' => null,
                'api_key' => null,
                'secret_key' => null
            ],
        ],
        [
            'item_tag' => 'baidu_face_ocr',
            'item_key' => 'face_ocr_plan',
            'item_type' => 'json',
            'item_value' => [
                'id' => null,
                'name' => null,
                'type' => null,
                'identification_method' => 'user_input',
            ]
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
