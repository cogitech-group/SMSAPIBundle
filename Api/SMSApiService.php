<?php

/*
 * This file is part of SMSAPIBundle
 *
 * (c) Krystian Karaś <k4rasq@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cogitech\SMSAPIBundle\Api;

use SMSApi\Api\SmsFactory;
use SMSApi\Client;
use SMSApi\Exception\SmsapiException;

class SMSApiService
{
    /**
     * Configuration passed from symfony config.
     *
     * @var array
     */
    private $config;

    /**
     * Sender name.
     *
     * @var string
     */
    private $sender;

    /**
     * API Client.
     *
     * @var SMSApi\Client
     */
    private $client;

    /**
     * Last error message.
     *
     * @var string
     */
    private $errorMessage;

    /**
     * Last error number.
     *
     * @var unknown
     */
    private $errorNo;

    public function __construct($config)
    {
        $this->init($config);
    }

    /**
     * Send SMS to client.
     *
     * @param string $to      Number of phone
     * @param string $message The message
     */
    public function sendSms($to, $message)
    {
        $this->errorMessage = '';
        $this->errorNo = 0;

        try {
            $smsapi = new SmsFactory();
            $smsapi->setClient($this->client);

            $actionSend = $smsapi->actionSend();
            $actionSend->setTo($to);
            $actionSend->setText($message);
            $actionSend->setSender($this->getSender());
            $actionSend->execute();
        } catch (SmsapiException $e) {
            $this->errorMessage = $e->getMessage();
            $this->errorNo = $e->getCode();

            return false;
        }

        return true;
    }

    /**
     * Get current sender name.
     *
     * @return string sender name
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set current sender name.
     *
     * @param string $val value
     *
     * @return self
     */
    public function setSender($val)
    {
        $this->sender = $val;

        return $this;
    }

    /**
     * Return last API error message.
     *
     * @return string
     */
    public function getLastErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Return last API error code.
     *
     * @return string
     */
    public function getLastErrorNo()
    {
        return $this->errorNo;
    }

    /**
     * Initialize connection.
     *
     * @param array $config configuration
     */
    protected function init($config)
    {
        // Init credentials
        switch ($config['authentication']['type']) {
            case 'token':
                $this->client = Client::createFromToken($config['authentication']['token']);
                break;
            case 'login':
                $this->client = new Client($config['authentication']['login']);
                $this->client->setPasswordHash(md5($config['authentication']['password']));
                break;
        }

        // Init sender
        $this->sender = $config['default_values']['sender'];
    }
}
