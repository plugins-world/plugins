<?php

namespace Plugins\FileManage\Console\Commands;

use Illuminate\Console\Command;
use Plugins\FileManage\Models\Transcode;
use Plugins\FileManage\Services\VideoService;

class FfmpegTranscodeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ffmpeg:transcode {file} 
        {--ddid= : 磁盘目录 ddid}
        {--fid= : 文件 fid}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mp4 视频转码为 hls 文件';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = $this->argument('file');

        $savePath = '/nfs/share/transcode/';
        $transcodeFinishPath = '/nfs/share/video/';


        $videoService = new VideoService();

        $videoService->usingBeforeTranscode(function (VideoService $service) {
            $transcode = Transcode::addTask([
                'ddid' => $this->option('ddid'),
                'fid' => $this->option('fid'),
                'type' => 'video',
                'origin_filepath' => $service->getOriginFile(),
                'transcode_filepath' => null,
                'transcode_finish_filepath' => null,
                'thumb_image' => null,
                'is_clean_origin_file' => null,
                'start_time' => null,
                'end_time' => null,
                'status' => Transcode::STATUS_WAITING,
                'step' => 'add_task',
                'step_message' => '添加转码任务',
                'is_notify' => false,
                'notify_result' => null,
            ]);

            $service->setTask($transcode);
        });

        $videoService->usingUpdateStepProcessTranscode(function (VideoService $service, $data) {
            /** @var Transcode */
            $transcode = $service->getTask();

            // 转码进度更新
            $data['tid'] = $transcode?->tid;
            $transcode::updateTask($data);
        });

        $videoService->usingAfterTranscode(function (VideoService $service) {
            dump($service->toArray());
            // 飞书通知，转码完成。
        });

        $videoService->setFile($file);
        $videoService->setSavePath($savePath);
        $videoService->setTranscodeFinishPath($transcodeFinishPath);

        if ($error = $videoService->getError()) {
            $this->error($error);
            return Command::FAILURE;
        }

        $videoService->handle();

        return Command::SUCCESS;
    }
}
