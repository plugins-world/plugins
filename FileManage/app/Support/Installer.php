<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\FileManage\Support;

use MouYong\LaravelConfig\Models\Config;

class Installer
{
    protected $config = [
        // [
        //     'item_tag' => 'file-manage',
        //     'item_key' => 'access_key',
        //     'item_type' => 'string',
        //     'item_value' => null,
        // ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'document_preview_url',
            'item_type' => 'string',
            'item_value' => 'https://wps-view.zhihuipk.com/?src=URL',
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'file_preview_url',
            'item_type' => 'string',
            'item_value' => 'https://file.keking.cn/onlinePreview?url=URL',
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'exclude_files',
            'item_type' => 'array',
            'item_value' => ['ts'],
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'enable_extract_subtitle',
            'item_type' => 'bool',
            'item_value' => false,
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'enable_generate_preview_image',
            'item_type' => 'bool',
            'item_value' => false,
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'enable_generate_stage_photo',
            'item_type' => 'bool',
            'item_value' => false,
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'enable_change_video_location_after_transcode_finish',
            'item_type' => 'bool',
            'item_value' => true,
        ],
        [
            'item_tag' => 'file-manage',
            'item_key' => 'enable_clean_origin_file',
            'item_type' => 'bool',
            'item_value' => false,
        ],
    ];

    public function process(callable $callable)
    {
        foreach ($this->config as $configItem) {
            $callable($configItem);
        }
    }

    // plugin install
    public function install()
    {
        Config::addKeyValues($this->config);
    }

    /// plugin uninstall
    public function uninstall()
    {
        Config::removeKeyValues($this->config);
    }
}
