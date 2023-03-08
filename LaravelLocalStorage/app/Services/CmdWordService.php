<?php

namespace Plugins\LaravelLocalStorage\Services;

use Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

class CmdWordService
{
    use CmdWordResponseTrait;

    protected $businessService;

    public function __construct()
    {
        $this->businessService = new BusinessService();
    }

    public function upload(array $data)
    {
        \validator()->validate($data, [
            'file' => ['required'],
            'path' => ['required', 'string'],
        ]);

        $result = $this->businessService->upload($data);

        return $this->success($result);
    }
}
