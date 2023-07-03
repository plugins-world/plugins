<?php

namespace Plugins\FileStorage\Models;

class File extends \Plugins\MarketManager\Models\Model
{
    const TYPE_IMAGE = 'image';
    const TYPE_VIDEO = 'video';
    const TYPE_AUDIO = 'audio';
    const TYPE_DOCUMENT = 'document';
    const TYPE_OTHERS = 'others';

    const TYPE_VOD = 'vod'; // m3u8, flv, dash...
    const TYPE_ZIP = 'zip';
    const TYPE_FILE = 'file';

    const TYPE_MAP = [
        FILE::TYPE_IMAGE => '图片',
        FILE::TYPE_VIDEO => '视频',
        FILE::TYPE_AUDIO => '音频',
        FILE::TYPE_DOCUMENT => '文档',
        FILE::TYPE_OTHERS => '其他文件',
    ];

    const EXTENSION_VIDEO = [
        'ts', 'mp4', 'mkv',
    ];
    const EXTENSION_DOCUMENT = [
        'txt', 'tsv',
        'wps', 'wpt', 'et', 'dps',
        'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'dot', 'pot', 'xlt', 'vsd', 'pdf', 'rtf',
        'odt', 'ods', 'odp', 'odg', 'odf',
    ];
    const EXTENSION_TSV = ['tsv'];
    const EXTENSION_VOD = ['m3u8', 'flv'];// m3u8, flv, dash...
    const EXTENSION_ZIP = ['zip', 'rar', '7z', 'tar', 'gz','bz2', 'cab', 'lz'];

    use Traits\FileServiceTrait;

    protected $casts = [
        'more_json' => 'json',
    ];

    public function usages()
    {
        return $this->hasMany(FileUsage::class);
    }
}
