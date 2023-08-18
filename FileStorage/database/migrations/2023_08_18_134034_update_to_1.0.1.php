<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Plugins\LaravelConfig\Utilities\ConfigUtility;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        ConfigUtility::addFresnsConfigItems([
            [
                'item_tag' => 'file_storage',
                'item_key' => 'file_storage_driver',
                'item_type' => 'string',
                'item_value' => 'local',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
