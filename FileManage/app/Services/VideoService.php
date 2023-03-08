<?php

namespace Plugins\FileManage\Services;

use Carbon\Traits\Macro;
use ZhenMu\Support\Utils\File;

class VideoService
{
    protected $file;

    protected $filename;

    protected $extension;

    protected $originFile;

    protected $transcodeFinishFilePath;

    protected $thumbImage;

    protected $savePath;

    protected $transcodeFinishPath;

    /**
     * @var callable
     */
    protected $transcodeFinishCallback;

    protected $config = [
        'enable_extract_subtitle' => false,
        'enable_generate_preview_image' => false,
        'enable_generate_stage_photo' => false,
        'enable_change_video_location_after_transcode_finish' => true,
        'enable_clean_origin_file' => false,
    ];

    // 视频基本信息
    protected $ffprobe_info = [];

    // 最大的清晰度
    protected $maxDefinition = null;

    // 最大的转码宽度
    protected $maxDefinitionWidth = null;

    // 最大清晰度的视频点播文件地址
    protected $maxWidthHlsFile = null;

    // 转码保存目录的映射关系
    protected $mp4_files = [];

    // 提取 MP4 视频的转码命令
    protected $ffmpeg_cmd = null;

    // 转码 hls + ts 执行的命令
    protected $segment_time = 5; // unit seconds, suggestion lt 10s

    protected $error = null;

    protected $task;

    protected $usingBeforeTranscode;

    protected $usingUpdateStepProcessTranscode;

    protected $usingAfterTranscode;

    public function __construct() {}

    /**
     * 转码前的数据进度保存处理
     */
    public function usingBeforeTranscode(callable $callback)
    {
        $this->usingBeforeTranscode = $callback;
    }

    /**
     * 转码前的数据进度保存处理
     */
    public function usingUpdateStepProcessTranscode(callable $callback)
    {
        $this->usingUpdateStepProcessTranscode = $callback;
    }

    /**
     * 转码后的通知处理
     */
    public function usingAfterTranscode(callable $callback)
    {
        $this->usingAfterTranscode = $callback;
    }

    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    public function getTask()
    {
        return $this->task;
    }

    public function setFile(string $file)
    {
        $this->file = $file;
        $this->filename = pathinfo($file, PATHINFO_FILENAME);;
        $this->extension = pathinfo($file, PATHINFO_EXTENSION);;
        $this->originFile = $file;

        return $this;
    }

    public function getFile()
    {
        if (!$this->file) {
            throw new \RuntimeException("请提供文件信息");
        }

        if (!file_exists($this->file)) {
            \info($this->error = "文件不存在: " . var_export([
                'file' => $this->file,
            ], true));

            throw new \RuntimeException($this->error);
        }

        $mimeType = File::mimeTypeFromPath($this->file);
        if (!str_contains($mimeType, 'video')) {
            \info($this->error = "文件不是视频: " . var_export([
                'mimeType' => $mimeType,
                'file' => $this->file,
            ], true));
            throw new \RuntimeException($this->error);
        }

        return $this->file;
    }

    public function getOriginFile()
    {
        return $this->originFile;
    }

    public function getOriginFileExtension()
    {
        return $this->originFile;
    }

    public function setSavePath(string $savePath)
    {
        $this->savePath = $savePath;

        return $this;
    }

    public function getSavePath()
    {
        if (!$this->savePath) {
            throw new \RuntimeException("请提供文件保存路径");
        }

        return $this->savePath;
    }

    public function setTranscodeFinishPath(string $path)
    {
        $this->transcodeFinishPath = $path;

        return $this;
    }

    public function getTranscodeFinishPath()
    {
        if (!$this->getConfig('enable_change_video_location_after_transcode_finish')) {
            return null;
        }

        if (!$this->transcodeFinishPath) {
            throw new \RuntimeException("请提供文件转码完成后原文件的保存路径");
        }

        return $this->transcodeFinishPath;
    }

    public function setConfig(array $config = [])
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function getConfig(?string $key = null, $default = null)
    {
        if (!$key) {
            return $this->config;
        }

        return $this->config[$key] ?? $default;
    }

    public function setTranscodeFinishCallback(callable $callback)
    {
        $this->transcodeFinishCallback = $callback;
    }

    public function validate()
    {
        $file = $this->getFile();
        $savePath = $this->getSavePath();
        $transcodeFinishPath = $this->getTranscodeFinishPath();

        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function ensureSavePathExists($path = null)
    {
        $dirpath = sprintf('%s/%s/', rtrim($this->getSavePath(), '/'), $this->getFilename());
        if ($path) {
            $dirpath = sprintf('%s/%s', rtrim($dirpath, '/'), ltrim($path, '/'));
        }

        return rtrim(File::ensurePathExists($dirpath), '/');
    }

    public function log($message, $writeToLaravel = true)
    {
        if ($writeToLaravel) {
            \info($message);
        }

        $message = sprintf("[%s]", date('Y-m-d H:i:s')) . $message . "\n";

        $logFilename = sprintf('%s/log.txt', $this->ensureSavePathExists());
        file_put_contents($logFilename, $message, FILE_APPEND);
    }

    public function perform(string $ffmpeg_cmd)
    {
        \ob_start();
        system($ffmpeg_cmd);
        $contents = \ob_get_contents();
        \ob_end_clean();

        return $contents;
    }

    public function ensureTranscodePathExists()
    {
        $this->perform(sprintf("rm -rf %s/*", $this->ensureSavePathExists()));

        // 创建转码结果输出的目录
        $dirs = ['hls', 'image_group', 'mp4', 'stage_photo', 'subtitle'];
        foreach ($dirs as $dir) {
            $subdir = $this->ensureSavePathExists($dir);
        }
    }

    public function updateStep()
    {
        if (is_callable($this->usingUpdateStepProcessTranscode)) {
            call_user_func_array($this->usingUpdateStepProcessTranscode, [$this, func_get_args()]);
        }
    }

    public function handle()
    {
        if (is_callable($this->usingBeforeTranscode)) {
            call_user_func($this->usingBeforeTranscode, $this);
        }

        \info("文件处理中: " . $this->file);

        try {
            $this->updateStep([
                'step' => 'before_validate',
                'step_message' => '转码验证中',
            ]);
            $this->validate();
        } catch (\Throwable $e) {
            $this->updateStep([
                'step' => 'after_validate',
                'step_message' => $e->getMessage(),
            ]);
            throw $e;
        }

        $this->updateStep([
            'step' => 'before_ensure_transcode_path_exists',
            'step_message' => '正在创建转码子目录',
        ]);
        $this->ensureTranscodePathExists();
        $this->updateStep([
            'step' => 'after_ensure_transcode_path_exists',
            'step_message' => '转码子目录创建成功',
        ]);

        // ========================第1步：获取视频基本信息======================== //
        $this->getMeta();

        // ========================第2步：提取视频字幕======================== //
        $this->extractSubtitle();
        // ========================第3步：将源视频转换成不同清晰度的 MP4 格式文件======================== //
        $this->extractMp4();
        // ========================第4步：将转码后文件转换成 HLS 格式（即 M3U8 + TS）======================== //
        $this->extractHlsAndTs();
        // ========================第5步：生成缩略图、预览图======================= //
        $this->generateThumbImageAndPreviewImage();
        // ========================第6步：生成剧照======================= //
        $this->generateStagePhoto();

        $this->moveOriginVideoToFinishVideo();

        $this->getMaxWidthHlsFile();

        \info("文件处理完成: " . $this->file);

        if (is_callable($this->usingAfterTranscode)) {
            call_user_func($this->usingAfterTranscode, $this);
        }
    }

    public function getMeta()
    {
        if (!$this->ffprobe_info) {
            if ($this->validate()) {
                $this->ensureTranscodePathExists();
            }

            $this->updateStep([
                'step' => 'before_get_meta',
                'step_message' => '正在获取视频基本信息',
            ]);

            $this->log("获取视频元数据信息中");

            $ffmpeg_cmd = sprintf('ffprobe -v quiet -print_format json -show_streams -show_format "%s"', $this->getFile());
            $output = $this->perform($ffmpeg_cmd);

            $this->ffprobe_info = json_decode($output, true);
            $this->log("获取视频元数据信息完成: \n" . $output);

            $this->updateStep([
                'step' => 'after_get_meta',
                'step_message' => '获取视频基本信息成功',
            ]);
        }

        // 获取视频流信息
        $video_stream = [];
        $audio_streams = [];
        $subtitle_streams = [];
        $attachment_streams = [];

        $ffprobe_info = $this->ffprobe_info;
        foreach ($ffprobe_info['streams'] as $stream) {
            if ($stream['codec_type'] == 'video') {
                $video_stream = $stream;
            } else if ($stream['codec_type'] == 'audio') {
                $audio_streams[] = $stream;
            } else if ($stream['codec_type'] == 'subtitle') {
                $subtitle_streams[] = $stream;
            } else if ($stream['codec_type'] == 'attachment') {
                $attachment_streams[] = $stream;
            }
        }

        # 只提取第一个音频流文件，暂不支持多音轨
        $audio_stream = $audio_streams[0];

        return [
            'filename' => $this->getFilename(),
            'video_stream' => $video_stream,
            'audio_streams' => $audio_streams,
            'audio_stream' => $audio_stream,
            'subtitle_streams' => $subtitle_streams,
            'attachment_streams' => $attachment_streams,
        ];
    }

    public function getVideoInfo(?string $key = null)
    {
        $video_stream = $this->getMetaByKey('video_stream');
        $audio_stream = $this->getMetaByKey('audio_stream');
        $vodeo_format = $this->getVideoFormat();

        $origin_width = $video_stream['width'];
        $origin_height = $video_stream['height'];
        $origin_bitrate  = $vodeo_format['bit_rate']; // 码率
        $origin_duration = $vodeo_format['duration']; // 时长，保留 2 位小数
        $origin_size = $vodeo_format['size']; // 大小

        $videoInfo = [];
        $videoInfo['video_stream'] = $video_stream;
        $videoInfo['audio_stream'] = $audio_stream;
        $videoInfo['vodeo_format'] = $vodeo_format;
        $videoInfo['origin_width'] = $origin_width;
        $videoInfo['origin_height'] = $origin_height;
        $videoInfo['origin_bitrate'] = $origin_bitrate; // 码率
        $videoInfo['origin_duration'] = $origin_duration; // 时长，保留 2 位小数
        $videoInfo['origin_size'] = $origin_size; // 大小

        return $videoInfo[$key] ?? $videoInfo;
    }

    public function getStreams()
    {
        return $this->ffprobe_info['streams'];
    }

    public function getVideoFormat()
    {
        return $this->ffprobe_info['format'];
    }

    public function getMetaByKey(string $key)
    {
        return $this->getMeta()[$key] ?? [];
    }

    public function extractSubtitle()
    {
        $this->updateStep([
            'step' => 'before_extract_subtitle',
            'step_message' => '正在提取字幕文件',
        ]);

        if (!$this->getConfig('enable_enable_extract_subtitle')) {
            $this->log('字幕文件提取功能未开启');

            $this->updateStep([
                'step' => 'after_extract_subtitle',
                'step_message' => '字幕文件提取功能未开启',
            ]);
            return;
        }

        $subtitle_streams = $this->getMetaByKey('subtitle_streams');

        $this->log('字幕文件提取中: ' . $this->getFile());
        foreach ($subtitle_streams as $subtitle_stream) {
            $subtitle_file = sprintf("%s/subtitle/%s.srt", $this->ensureSavePathExists(), $subtitle_stream['index']);

            $ffmpeg_cmd = sprintf("ffmpeg -v quiet -analyzeduration 100000000 -i '%s' -map 0:%s -y '%s'", $this->getFile(), $subtitle_stream['index'], $subtitle_file);

            $this->log(sprintf("提取第 %s 个字幕文件中", $subtitle_stream['index']));
            $output = $this->perform($ffmpeg_cmd);
            $this->log(sprintf("提取第 %s 个字幕文件完成\n%s", $subtitle_stream['index'], $output), false);
        }
        $this->log('字幕文件提取完成: ' . $this->getFile());


        $this->updateStep([
            'step' => 'after_extract_subtitle',
            'step_message' => '字幕文件提取成功',
        ]);
    }

    public function extractMp4()
    {
        $this->updateStep([
            'step' => 'before_extract_mp4',
            'step_message' => 'MP4 文件转码关系提取中',
        ]);

        $this->log('MP4 文件转码关系提取中: ' . $this->getFile());

        $video_stream = $this->getVideoInfo('video_stream');
        $audio_stream = $this->getVideoInfo('audio_stream');
        $vodeo_format = $this->getVideoInfo('vodeo_format');

        $origin_width = $this->getVideoInfo('origin_width');
        $origin_height = $this->getVideoInfo('origin_height');
        $origin_bitrate = $this->getVideoInfo('origin_bitrate');
        $origin_duration = $this->getVideoInfo('origin_duration');
        $origin_size = $this->getVideoInfo('origin_size');

        // 清晰度定义
        $definitions = [
            1, // 标清 640
            2, // 高清 1024
            3, // 超清 1280
            4, // 1080P 1920
            5, // 4K 3940
            100 // 原画
        ];

        // 清晰度与码率的对应关系
        $bitrates = [
            // 清晰度 => [总码率, 音频码率]
            1 => [600, 48],
            2 => [900, 72],
            3 => [1200, 128],
            4 => [2400, 192],
            5 => [6000, 256]
        ];

        // 清晰度与视频分辨率（宽度）的对应关系
        $widths = [
            // 清晰度 => 分辨率（视频宽度）
            1 => 640,
            2 => 1024,
            3 => 1280,
            4 => 1920,
            5 => 3840
        ];


        // 拼接转码命令
        $ffmpeg_cmd = sprintf("ffmpeg -analyzeduration 100000000 -i '%s' -sn -dn", $this->getFile());
        // 提高视频音量
        $increase_volume = '';
        // 获取片源的音频信息
        $ffmpeg_cmd = sprintf("ffmpeg -i '%s' -map 0:a -q:a 0 -af volumedetect -f null null 2>&1", $this->getFile());

        $this->log("获取片源的音频信息中: " . $this->getFile());
        $output = $this->perform($ffmpeg_cmd);
        $this->log("获取片源的音频信息完成: " . $this->getFile(), false);

        if ($output ?? null) {
            $volume_info = [];

            $lines = explode("\n", $output ?? '');
            foreach ($lines as $line) {
                if (strpos($line, 'Parsed_volumedetect_') !== false) {
                    $exploded = explode(':', substr($line, strpos($line, ']') + 1));
                    $volume_info[trim($exploded[0])] = (float) trim($exploded[1]);
                }
            }

            // 判断是否需要通过转码提高音量
            if (isset($volume_info['mean_volume']) && $volume_info['mean_volume'] + 13 < 0.1) {
                $incrdB = -$volume_info['mean_volume'] + 13;
                $increase_volume = "-af volume={$incrdB}dB";
            }
        }

        // 存储清晰度与转码后文件的对应关系，用于生成 HLS 文件
        $mp4_files = [];

        // 拼接各清晰度对应的转码命令
        foreach ($definitions as $definition) {

            if ($definition == 100) {
                // 原画特殊处理，只有特殊尺寸的视频才会转原画
                $width = $origin_width;
                $height = $origin_height;

                if ($width >= 710 && $width < 1024) {
                    $video_bitrate = $width * 620 / 1024 + 230; // [660, 850)
                    $audio_bitrate = 72;
                } else if ($width >= 1420 && $width < 1920) {
                    $video_bitrate = $width * 4032 / 1920 - 1782; // [1200, 2250)
                    $audio_bitrate = 192;
                } else if ($width >= 2170 && $width < 3840) {
                    $video_bitrate = $width * 7358 / 3840 - 1558; // [2600, 5800)
                    $audio_bitrate = 256;
                } else {
                    continue;
                }
            } else if ($definition == 1 && $origin_width < 710) {
                // 如原视频宽度小于 710，此时的视频只转一个清晰度
                $width = $origin_width;
                $height = $origin_height;
                $video_bitrate = $width * 650 / 710;
                $audio_bitrate = 72;
            } else {
                // 其他清晰度处理
                $width = $widths[$definition];
                if ($origin_width < $width) {
                    // 若原视频宽度小于该清晰度对应的宽度则不转
                    continue;
                }
                // 视频高自适应
                $height = intval($width / $origin_width * $origin_height);

                // 总码率，音频码率
                list($bitrate, $audio_bitrate)  = $bitrates[$definition];
                // 视频码率
                $video_bitrate = $bitrate - $audio_bitrate;
            }

            // 1080P、4K、原画的音频码率 = 固定音频码率 x 声道数
            if (in_array($definition, [4, 5, 100]) && !empty($audio_stream['channels'])) {
                $audio_bitrate *= $audio_stream['channels'];
            }

            // 转码后的视频存储位置
            $mp4_file = sprintf("%s/mp4/{$definition}.mp4", $this->ensureSavePathExists());

            // 拼接各清晰度的转码命令
            $cmd = '';
            $cmd .= " -t {$origin_duration}";
            $video_cmd = '';
            $audio_cmd = '';
            $other_cmd = '';

            // 根据清晰度使用不同的转码命令
            if ($definition == 1) {
                // 标清
                $video_cmd = " -map 0:{$video_stream['index']} -vsync 1";
                $video_cmd .= " -c:v libx264 -b:v {$video_bitrate}k -r 15";
                $video_cmd .= " -s {$width}x{$height}";
                $video_cmd .= " -aspect {$width}:{$height}";

                $audio_cmd .= " -map 0:{$audio_stream['index']} -c:a aac -strict -2 -b:a {$audio_bitrate}k";
                $audio_cmd .= " -ar 44100 -ac 1 {$increase_volume}";

                $other_cmd = " -qdiff 4 -qcomp 0.6 -subq 9 -preset slower -me_range 32";
                $other_cmd .= " -coder ac -me_method umh -pix_fmt yuv420p";
                $other_cmd .= " -keyint_min 15";
                $other_cmd .= " -refs 4 -bf 4 -movflags +faststart";
            } else if ($definition == 2) {
                // 高清
                $video_cmd = " -map 0:{$video_stream['index']} -vsync 1";
                $video_cmd .= " -c:v libx264 -b:v {$video_bitrate}k -r 15";
                $video_cmd .= " -s {$width}x{$height}";
                $video_cmd .= " -aspect {$width}:{$height}";

                $audio_cmd .= " -map 0:{$audio_stream['index']} -c:a aac -strict -2 -b:a {$audio_bitrate}k";
                $audio_cmd .= " -ar 44100 -ac 1 {$increase_volume}";

                $other_cmd = " -qdiff 4 -qcomp 0.6 -subq 9 -preset slower -me_range 32";
                $other_cmd .= " -coder ac -me_method umh -pix_fmt yuv420p";
                $other_cmd .= " -keyint_min 15";
                $other_cmd .= " -refs 3 -bf 3 -movflags +faststart";
            } else if ($definition == 3) {
                // 超清
                $video_cmd = " -map 0:{$video_stream['index']} -vsync 1";
                $video_cmd .= " -c:v libx264 -b:v {$video_bitrate}k -r 20";
                $video_cmd .= " -s {$width}x{$height}";
                $video_cmd .= " -aspect {$width}:{$height}";

                $audio_cmd .= " -map 0:{$audio_stream['index']} -c:a aac -strict -2 -b:a {$audio_bitrate}k";
                $audio_cmd .= " -ar 44100 -ac 2 {$increase_volume}";

                $other_cmd = " -qdiff 4 -qcomp 0.6 -subq 9 -preset slower -me_range 32";
                $other_cmd .= " -coder ac -me_method umh -pix_fmt yuv420p";
                $other_cmd .= " -keyint_min 20";
                $other_cmd .= " -refs 3 -bf 2 -movflags +faststart";
            } else {
                // 1080P、4K、原画
                $video_cmd = " -map 0:{$video_stream['index']} -vsync 1";
                $video_cmd .= " -c:v libx264 -b:v {$video_bitrate}k -r 25";
                $video_cmd .= " -s {$width}x{$height}";
                $video_cmd .= " -aspect {$width}:{$height}";

                $audio_cmd .= " -map 0:{$audio_stream['index']} -c:a aac -strict -2 -b:a {$audio_bitrate}k";
                $audio_cmd .= " -ar 44100 {$increase_volume}";

                $other_cmd = " -qdiff 4 -qcomp 0.6 -subq 9 -preset slower -me_range 32";
                $other_cmd .= " -coder ac -me_method umh -pix_fmt yuv420p";
                $other_cmd .= " -keyint_min 25";
                $other_cmd .= " -refs 3 -bf 2 -movflags +faststart";
            }

            $cmd .= $video_cmd . $audio_cmd . $other_cmd;
            $cmd .= " -y '{$mp4_file}'";

            $ffmpeg_cmd .= " \\\n";
            $ffmpeg_cmd .= $cmd;

            $mp4_files[$definition] = $mp4_file;
        }

        $targetDefinitions = array_keys($mp4_files);
        $this->maxDefinition = count($targetDefinitions) ? end($targetDefinitions) : null;
        $this->maxDefinitionWidth = $widths[$this->maxDefinition] ?? $origin_width;
        $this->mp4_files = $mp4_files;
        $this->ffmpeg_cmd = $ffmpeg_cmd;

        $this->log("MP4 文件转码关系提取完成: " . $this->getFile());
        $this->log("最大清晰度为: " . $this->maxDefinitionWidth);
        $this->log("MP4 文件转码关系结果:\n" . json_encode($mp4_files, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->log("MP4 执行转码的命令:\n" . $ffmpeg_cmd);

        $this->updateStep([
            'step' => 'after_extract_mp4',
            'step_message' => 'MP4 文件转码关系提取完成',
        ]);
        $this->transcodeMp4();
    }

    public function transcodeMp4()
    {
        $this->updateStep([
            'step' => 'before_transcode_mp4',
            'step_message' => 'MP4 文件生成中',
        ]);

        $this->log("MP4 文件生成中: " . $this->getFile());
        $ffmpeg_cmd = $this->ffmpeg_cmd;
        $output = $this->perform($ffmpeg_cmd);
        $this->log("MP4 文件生成完成: \n" . $output ?: '无');

        $this->updateStep([
            'step' => 'before_transcode_mp4',
            'step_message' => 'MP4 文件生成完成',
        ]);
    }

    public function extractHlsAndTs()
    {
        $this->updateStep([
            'step' => 'before_extract_hls_and_ts',
            'step_message' => 'hls 文件生成中',
        ]);

        $mp4_files = $this->mp4_files;
        $video_path = $this->ensureSavePathExists();

        $segment_time = $this->segment_time; // 10

        foreach ($mp4_files as $definition => $mp4_file) {
            $m3u8_file = $video_path . "/hls/{$definition}.m3u8";
            $ts_file = $video_path . "/hls/{$definition}_%05d.ts";

            $ffmpeg_cmd = "ffmpeg -i '{$mp4_file}' -map 0 -c copy -c:v libx264 -f segment"; // success
            // $ffmpeg_cmd = "ffmpeg -i '{$mp4_file}' -map 0 -c copy -bsf:v h264_mp4toannexb -f segment"; // 202
            // $ffmpeg_cmd = "ffmpeg -i '{$mp4_file}' -map 0 -c copy -bsf hevc_mp4toannexb -f segment"; // go2
            $ffmpeg_cmd .= " -segment_list '{$m3u8_file}' -segment_time {$segment_time} -y '{$ts_file}'";

            $this->log("hls 文件生成中: " . $mp4_file);
            $output = $this->perform($ffmpeg_cmd);
            $this->log("hls 文件生成完成: " . $mp4_file);
            $this->log("hls 文件生成日志: \n" . $output ?: '无');

            $this->maxWidthHlsFile = $m3u8_file;
        }

        $this->updateStep([
            'step' => 'after_extract_hls_and_ts',
            'step_message' => 'hls 文件生成完成',
        ]);
    }

    public function getMaxWidthHlsFile()
    {
        $this->log("最大清晰度的视频文件地址是: " . $this->maxWidthHlsFile);

        return $this->maxWidthHlsFile;
    }

    public function generateThumbImageAndPreviewImage()
    {
        $this->updateStep([
            'step' => 'before_generate_thumb_image_and_preview_image',
            'step_message' => '正在生成封面图',
        ]);

        $mp4_files = $this->mp4_files;
        $video_path = $this->ensureSavePathExists();

        $video_stream = $this->getVideoInfo('video_stream');
        $audio_stream = $this->getVideoInfo('audio_stream');
        $vodeo_format = $this->getVideoInfo('vodeo_format');

        $origin_width = $this->getVideoInfo('origin_width');
        $origin_height = $this->getVideoInfo('origin_height');
        $origin_bitrate = $this->getVideoInfo('origin_bitrate');
        $origin_duration = $this->getVideoInfo('origin_duration');
        $origin_size = $this->getVideoInfo('origin_size');

        // 使用最高清晰度的视频生成缩略图和预览图
        $mp4_file = end($mp4_files);

        // 缩略图
        $thumb_width = 220;
        $thumb_height = floor($thumb_width / $origin_width * $origin_height); // 高度自适应
        $thumb_file = $video_path . '/thumb.jpg';
        $thumb_start = $origin_duration * 0.5; // 在视频中间位置截取一张

        $ffmpeg_cmd = "ffmpeg -analyzeduration 100000000 -i '{$mp4_file}' -vsync 0 -ss {$thumb_start} -frames:v 1 -s {$thumb_width}x{$thumb_height} -f image2 -y '{$thumb_file}'";

        if ($this->getConfig('enable_generate_preview_image')) {
            // 预览图，这里的参数应根据实际需要自行调整，这里仅仅为了演示
            $oi_interval = 2; // 预览图间隔：每2秒一张
            $oi_width = 160;
            $oi_height = floor($oi_width / $origin_width * $origin_height);
            $rows = 5; // 每张5行
            $cols = 6; // 每张6列
            $oi_file = $video_path . "/image_group/%d.jpg";

            $ffmpeg_cmd .= " -vsync 1 -vf 'fps=1/{$oi_interval},scale={$oi_width}:{$oi_height},tile={$cols}x{$rows}' -f image2 -y '{$oi_file}'";
        }

        $this->thumbImage = $thumb_file;

        $this->log("封面图生成中: " . $mp4_file);
        $output = $this->perform($ffmpeg_cmd);
        $this->log("封面图生成完成: " . $mp4_file);
        $this->log("封面图生成日志: \n" . $output ?: '无');

        $this->updateStep([
            'step' => 'after_generate_thumb_image_and_preview_image',
            'step_message' => '封面图生成成功',
        ]);
    }

    public function getThumbImage()
    {
        return $this->thumbImage;
    }

    public function generateStagePhoto()
    {
        $this->updateStep([
            'step' => 'before_generate_stage_photo',
            'step_message' => '正在生成剧照',
        ]);
        if (!$this->getConfig('enable_generate_stage_photo')) {
            $this->log('剧照生成功能未开启');
            return;
        }

        $mp4_files = $this->mp4_files;
        $video_path = $this->ensureSavePathExists();

        $video_stream = $this->getVideoInfo('video_stream');
        $audio_stream = $this->getVideoInfo('audio_stream');
        $vodeo_format = $this->getVideoInfo('vodeo_format');

        $origin_width = $this->getVideoInfo('origin_width');
        $origin_height = $this->getVideoInfo('origin_height');
        $origin_bitrate = $this->getVideoInfo('origin_bitrate');
        $origin_duration = $this->getVideoInfo('origin_duration');
        $origin_size = $this->getVideoInfo('origin_size');

        // 使用最高清晰度的视频生成剧照
        $mp4_file = end($mp4_files);

        // 剧照
        $sp_count = 30; // 生成的剧照数量
        $sp_from = $origin_duration * 0.1; // 生成剧照的开始时间
        $sp_to = $origin_duration * 0.9; // 生成剧照的结束时间
        $sp_long = $sp_to - $sp_from; // 生成快照的视频时长
        $sp_interval = $sp_long / $sp_count; // 生成快照的时间间隙
        $sp_file = $video_path . '/stage_photo/%03d.jpg';

        $ffmpeg_cmd = "ffmpeg -analyzeduration 100000000 -ss {$sp_from} -i '{$mp4_file}' -map 0:v -t {$sp_long} -vf 'fps=fps=1/{$sp_interval}' -f image2 -y '{$sp_file}'";

        $this->log("剧照生成中: " . $mp4_file);
        $output = $this->perform($ffmpeg_cmd);
        $this->log("剧照生成完成: " . $mp4_file);
        $this->log("剧照生成日志: \n" . $output ?: '无');
        $this->updateStep([
            'step' => 'before_generate_stage_photo',
            'step_message' => '剧照生成成功',
        ]);
    }

    public function moveOriginVideoToFinishVideo()
    {
        $originFile = $this->getOriginFile();

        $this->log('转码完成，原文件整理中: '.$originFile);
        if (!$this->getConfig('enable_change_video_location_after_transcode_finish')) {
            return null;
        }

        if (!$this->getTranscodeFinishPath()) {
            $this->log('未提供转码完成后的原视频文件保存目录');
            return;
        }

        $oldFilepath = $originFile;
        $newFilepath = $this->getTranscodeFinishPath() . DIRECTORY_SEPARATOR . basename($oldFilepath);
        $this->transcodeFinishFilePath = $newFilepath;

        @copy($oldFilepath, $newFilepath);

        $this->log('转码完成，原文件整理完成: '.$newFilepath);

        if ($this->getConfig('enable_clean_origin_file')) {
            $this->log(sprintf('还原文件路径命令: cp %s %s', $newFilepath, $oldFilepath));
            @unlink($originFile);
        }
    }

    public function getTranscodeFinishFilePath()
    {
        return $this->transcodeFinishFilePath;
    }

    public function toArray()
    {
        $videoInfo = [];
        $videoInfo['origin_filepath'] = $this->getOriginFile(); // 原文件路径
        $videoInfo['hls_filepath'] = $this->getMaxWidthHlsFile(); // 转码 hls 路径
        $videoInfo['transcode_finish_filepath'] = $this->getTranscodeFinishFilePath(); // 转码 hls 路径
        $videoInfo['extension'] = $this->getOriginFileExtension(); // 后缀
        $videoInfo['thumb_image'] = $this->getThumbImage(); // 封面图

        return $videoInfo;
    }
}
