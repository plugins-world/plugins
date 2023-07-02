<?php

namespace Plugins\FileStorage\Models\Traits;

use Plugins\FileStorage\Models\File;
use Plugins\FileStorage\Models\FileUsage;

/**
 * @mixin File
 */

 trait FileServiceTrait
 {
     public function addFileUsageRecord(array $fileInfo)
     {
         $file = $this;
 
         // 必须外部传入的参数
         $usageType = $fileInfo['usage_type'];
         $tableName = $fileInfo['table_name'];
         $tableColumn = $fileInfo['table_column'];
         $tableId = $fileInfo['table_id'];
         $tableValue = $fileInfo['table_value'] ?? null;

         $params = [
             'file_id' => $file['id'],
             'file_type' => $file['type'],
             'usage_type' => $usageType,
             'table_name' => $tableName,
             'table_column' => $tableColumn,
             'table_id' => $tableId,
             'table_value' => $tableValue,
         ];
 
         $data = $params + [
             'rating' => $fileInfo['rating'] ?? 0,
             'remark' => $fileInfo['remark'] ?? null,
         ];
 
         $usage = FileUsage::updateOrCreate($params, $data);
 
         return $usage;
     }
 
     public function getFileInfo()
     {
         $file = $this;
 
         $fileInfo['fid'] = $file['fid'];
         $fileInfo['type'] = $file['type'];
         $fileInfo['name'] = $file['name'];
         $fileInfo['mime'] = $file['mime'];
         $fileInfo['extension'] = $file['extension'];
         $fileInfo['size'] = $file['size'];
         $fileInfo['md5'] = $file['md5'];
         $fileInfo['sha'] = $file['sha'];
         $fileInfo['sha_type'] = $file['sha_type'];
         $fileInfo['path'] = $file['path'];
         $fileInfo['url'] = null;
         switch ($fileInfo['type']) {
             case File::TYPE_IMAGE:
                 $fileInfo['image_width'] = $file['image_width'];
                 $fileInfo['image_height'] = $file['image_height'];
                 break;
 
             case File::TYPE_IMAGE:
                 $fileInfo['audio_time'] = $file['audio_time'];
                 break;
 
             case File::TYPE_IMAGE:
                 $fileInfo['video_time'] = $file['video_time'];
                 $fileInfo['video_poster_path'] = $file['video_poster_path'];
                 break;
         }
         $fileInfo['more_json'] = $file['more_json'];
         $fileInfo['transcoding_state'] = $file['transcoding_state'];
         $fileInfo['transcoding_reason'] = $file['transcoding_reason'];
         $fileInfo['original_path'] = $file['original_path'];
         $fileInfo['is_physical_delete'] = $file['is_physical_delete'];
 
         $fileInfo['usages'] = [];
         foreach ($file['usages'] as $usage) {
             $usageInfo['usage_type'] = $usage['usage_type'];
             $usageInfo['table_name'] = $usage['table_name'];
             $usageInfo['table_column'] = $usage['table_column'];
             $usageInfo['table_id'] = $usage['table_id'];
             $usageInfo['table_value'] = $usage['table_value'];
             $usageInfo['rating'] = $usage['rating'];
             $usageInfo['remark'] = $usage['remark'];
 
             $fileInfo['usages'][] = $usageInfo;
         }
 
         return $fileInfo;
     }
 
     public function getFileInfoWithUsages()
     {
         $fileInfo = $this->getFileInfo();
 
         $usages = [];
         foreach ($fileInfo['usages'] as $usage) {
             $fileInfoData = collect($fileInfo)->except('usages')->all();
             $usages[] = array_merge($fileInfoData, $usage);
         }
 
         return $usages;
     }
 }