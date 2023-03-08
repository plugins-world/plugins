<?php

namespace Plugins\SystemAuthorization\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Plugins\SystemAuthorization\Entities\AuthCode;
use Plugins\SystemAuthorization\Mail\AuthCodeExpired;
use Plugins\SystemAuthorization\Models\AuthCode as AuthCodeModel;
use Plugins\SystemAuthorization\Services\AuthCodeService;
use Plugins\SystemAuthorization\Services\CustomerService;

class AuthCodeExpiredCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth-code:expire-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected  $customerService;
    protected  $authCodeService;

    public function __construct()
    {
        parent::__construct();
        $this->customerService = app(CustomerService::class);
        $this->authCodeService = app(AuthCodeService::class);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $authCodes = AuthCodeModel::where('is_expired', false)->get();

        foreach ($authCodes as $authCode) {
            if ($authCode->isValid()) {
                continue;
            }

            $this->updateAuthCodeToExpired($authCode);
            $this->notifyAuthCodeExpired($authCode);
//            dump($authCode);
        }

        return Command::SUCCESS;
    }

    private function updateAuthCodeToExpired(AuthCodeModel $authCode)
    {
        $authCode->update(['is_expired' => true]);
    }

    private function notifyAuthCodeExpired(AuthCodeModel $authCode)
    {
        $customerEntity = $this->customerService->find($authCode->customer_id);
        $authCodeEntity = new AuthCode();
        $authCodeEntity->setEndTime($authCode->end_time);
        $authCodeEntity->setCustomer($customerEntity);

        Mail::to('1139038165@qq.com')->send(new AuthCodeExpired($authCodeEntity));
    }

}
