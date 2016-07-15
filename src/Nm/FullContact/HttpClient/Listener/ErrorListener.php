<?php

namespace Nm\FullContact\HttpClient\Listener;

use Nm\FullContact\Exception\TwoFactorAuthenticationRequiredException;
use Nm\FullContact\HttpClient\Message\ResponseMediator;
use Guzzle\Common\Event;
use Nm\FullContact\Exception\ApiLimitExceedException;
use Nm\FullContact\Exception\ErrorException;
use Nm\FullContact\Exception\RuntimeException;
use Nm\FullContact\Exception\ValidationFailedException;

/**
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class ErrorListener
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Event $event
     * @throws ApiLimitExceedException
     * @throws NotFoundException
     * @throws RuntimeException
     */
    public function onRequestError(Event $event)
    {
        /** @var $request \Guzzle\Http\Message\Request */
        $request = $event['request'];
        $response = $request->getResponse();

        if ($response->isClientError() || $response->isServerError()) {
            if (403 === $response->getStatusCode()) {
                $limit = (integer) $response->getHeader('X-Rate-Limit-Limit');
                $remaining = (integer) $response->getHeader('X-Rate-Limit-Remaining');
                $reset = (integer) $response->getHeader('X-Rate-Limit-Reset');

                if ($limit === 0) {
                    throw new ApiLimitExceedException("X-Rate-Limit-Limit retched : 600 requests authorized", 403);
                } elseif ($remaining === 0) {
                    throw new ApiLimitExceedException("X-Rate-Limit-Remaining retched : 10 requests per second authorized. You need to wait $reset seconds", 403);
                }
            }

            if (404 === $response->getStatusCode() && $response->getHeader('reasonCode')) {
                throw new NotFoundException($response->getHeader('reasonCode'), 404);
            }

            $content = ResponseMediator::getContent($response);
            throw new RuntimeException(isset($content['message']) ? $content['message'] : $content, $response->getStatusCode());
        }
    }
}
