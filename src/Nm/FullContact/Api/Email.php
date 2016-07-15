<?php

namespace Nm\FullContact\Api;

/**
 * Email Api.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class Email extends AbstractApi
{
    const PATH = '/email';

    /**
     *  Identifying email addresses that either use sub addressing or are associated with
     *  known one time use or disposable email addresses.
     *
     * @param string $email   URL requested once the card has been processed
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function disposable($email, array $options = array())
    {
        $options = array_merge(['email' => $email], $options);

        return $this->getData(self::PATH.'/disposable', $options);
    }
}
