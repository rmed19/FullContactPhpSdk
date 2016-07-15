<?php

namespace Nm\FullContact\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Nm\FullContact\Exception\TwoFactorAuthenticationRequiredException;
use Nm\FullContact\Exception\ErrorException;
use Nm\FullContact\Exception\RuntimeException;
use Nm\FullContact\HttpClient\Listener\ErrorListener;
use Nm\FullContact\HttpClient\Listener\AuthListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Performs requests on FullContact API. API documentation should be self-explanatory.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var array
     */
    protected $options = array(
        'base_url' => 'https://api.fullcontact.com',

        'user_agent' => 'nm-fullcontact-client (https://medrhamnia.wordpress.com)',
        'timeout' => 10,

        'api_limit' => 600,
        'api_version' => 'v2',
        'format' => 'json',
        'cache_dir' => null,
    );
    protected $headers = array();
    private $lastResponse;
    private $lastRequest;

    /**
     * HttpClient constructor.
     *
     * @param array                $options
     * @param ClientInterface|null $client
     */
    public function __construct(array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);
        $client = $client ?: new GuzzleClient($this->options['base_url'], $this->options);
        $this->client = $client;

        $this->addListener('request.error', array(new ErrorListener($this->options), 'onRequestError'));
        $this->clearHeaders();
    }

    /**
     * {@inheritdoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers.
     */
    public function clearHeaders()
    {
        $this->headers = array(
            'Accept' => sprintf('application/vnd.Nm.%s+json', $this->options['api_version']),
            'User-Agent' => sprintf('%s', $this->options['user_agent']),
        );
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string   $eventName The event to listen on
     * @param callable $listener  The listener
     */
    public function addListener($eventName, $listener)
    {
        $this->client->getEventDispatcher()->addListener($eventName, $listener);
    }

    /**
     * Adds an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->client->addSubscriber($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        $url = sprintf('%s.%s', $path, $this->options['format']);

        return $this->request($url, null, 'GET', $headers, array('query' => $parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function patch($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $body, array $headers = array())
    {
        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function request($path, $body = null, $httpMethod = 'GET', array $headers = array(), array $options = array())
    {
        $request = $this->createRequest($httpMethod, $path, $body, $headers, $options);

        try {
            $response = $this->client->send($request);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        } catch (TwoFactorAuthenticationRequiredException $e) {
            throw $e;
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }

        $this->lastRequest = $request;
        $this->lastResponse = $response;

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate($password)
    {
        $this->addListener('request.before_send', array(
            new AuthListener($password), 'onRequestBeforeSend',
        ));
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    protected function createRequest($httpMethod, $path, $body = null, array $headers = array(), array $options = array())
    {
        return $this->client->createRequest(
            $httpMethod,
            $path,
            array_merge($this->headers, $headers),
            $body,
            $options
        );
    }
}
