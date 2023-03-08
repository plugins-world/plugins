<?php

namespace Plugins\SystemAuthorization\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;


class AuthCodeExpired extends Mailable
{
    use Queueable, SerializesModels;

    public $authCodeEntity;

    /**
     * Create a new message instance.
     *
     * @param AuthCodeEntity $authCodeEntity
     * @return void
     */
    public function __construct(AuthCodeEntity $authCodeEntity)
    {
        $this->authCodeEntity = $authCodeEntity;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('SystemAuthorization::auth-codes.expired-notify')
            ->with([
                'name' => $this->authCodeEntity->getCustomer()->getCustomerName(),
                'expiration_date' => $this->authCodeEntity->getEndTime()
            ]);
    }
}
