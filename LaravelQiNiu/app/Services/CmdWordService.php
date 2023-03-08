<?php

namespace Plugins\LaravelQiNiu\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    protected $businessService;

    public function __construct()
    {
        $this->businessService = new BusinessService();
    }

    public function getToken(array $data): array
    {
        \validator()->validate($data, [
            'name' => ['required', 'string'],
            'expire_time' => ['nullable', 'integer'],
        ]);

        $result = $this->businessService->getToken($data);

        return $this->success($result);
    }

    public function upload(array $data)
    {
        \validator()->validate($data, [
            'file' => ['required', 'file'],
            'path' => ['required', 'string'],
        ]);

        $result = $this->businessService->upload($data);

        return $this->success($result);
    }
}
