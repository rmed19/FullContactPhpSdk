<?php

namespace Nm\FullContact\HttpClient\Message;

use Guzzle\Http\Message\Response;
use Nm\FullContact\Exception\ApiLimitExceedException;

/**
 * Class ResponseMediator
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class ResponseMediator
{
    /**
     * Parse response content
     *
     * @param Response $response
     * @return \Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public static function getContent(Response $response)
    {
        $body = $response->getBody(true);
        $content = json_decode($body, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return $body;
        }

        return $content;
    }

    /**
     * @param Response $response
     * @return array|void
     */
    public static function getPagination(Response $response)
    {
        $header = $response->getHeader('page');

        if (empty($header)) {
            return;
        }

        $pagination = array();
        foreach (explode(',', $header) as $link) {
            preg_match('/<(.*)>; rel="(.*)"/i', trim($link, ','), $match);

            if (3 === count($match)) {
                $pagination[$match[2]] = $match[1];
            }
        }

        return $pagination;
    }

    /**
     * @param Response $response
     * @return \Guzzle\Http\Message\Header|mixed|null
     */
    public static function getApiLimit(Response $response)
    {
        $remainingCalls = $response->getHeader('X-Rate-Limit-Limit');

        if (null !== $remainingCalls && 1 > $remainingCalls) {
            throw new ApiLimitExceedException($remainingCalls);
        }

        return $remainingCalls;
    }
}
