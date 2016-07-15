<?php

namespace Nm\FullContact\Api;

/**
 * CardReader Api.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class CardReader extends AbstractApi
{
    const PATH = '/cardReader';

    /**
     * Submit front and back photos of a business card for processing.
     *
     * @param string $webhookUrl URL requested once the card has been processed
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function upload($webhookUrl, array $options = array())
    {
        $options = array_merge(['webhookUrl' => $webhookUrl], $options);

        return $this->getData(self::PATH, $options);
    }
}
