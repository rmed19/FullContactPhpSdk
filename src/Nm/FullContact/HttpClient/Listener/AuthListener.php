<?php

namespace Edatis\HttpClient\Listener;

use Guzzle\Common\Event;

/**
 * Class AuthListener
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class AuthListener
{
    /**
     * The api key
     *
     * @var string
     */
    private $password;

    /**
     * AuthListener constructor.
     *
     * @param srtring $password the api key
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Event to be execute before sending any request
     *
     * @param Event $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        $event['request']->setHeader('X-FullContact-APIKey', $this->password);
    }
}
