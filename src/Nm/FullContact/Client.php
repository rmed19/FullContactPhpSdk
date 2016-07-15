<?php

namespace Nm\FullContact;

use Nm\FullContact\Api\ApiInterface;
use Nm\FullContact\Exception\InvalidArgumentException;
use Nm\FullContact\Exception\BadMethodCallException;
use Nm\FullContact\HttpClient\HttpClient;
use Nm\FullContact\HttpClient\HttpClientInterface;

/**
 * Simple yet very cool PHP FullContact client.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 *
 * Website: https://medrhamnia.wordpress.com
 */
class Client
{
    /**
     * @var array
     */
    private $options = array(
        'base_url' => 'https://api.fullcontact.com',

        'user_agent' => 'nm-fullcontact-client (https://medrhamnia.wordpress.com)',
        'timeout' => 10,

        'api_limit' => 600,
        'api_version' => 'v2',

        'cache_dir' => null,
        'format' => 'json',
    );

    /**
     * The Buzz instance used to communicate with FullContact.
     *
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Instantiate a new FullContact client.
     *
     * @param null|HttpClientInterface $httpClient FullContact http client
     */
    public function __construct(HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name)
    {
        switch ($name) {
            case 'person':
                $api = new Api\Person($this);
                break;

            case 'company':
                $api = new Api\Company($this);
                break;
            case 'card-reader':
                $api = new Api\CardReader($this);
                break;

            case 'email':
                $api = new Api\Email($this);
                break;

            case 'name':
                $api = new Api\Name($this);
                break;

            case 'location':
                $api = new Api\Location($this);
                break;

            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param null|string $password FullContact Api Key
     *
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate($password = null)
    {
        if (null === $password) {
            throw new InvalidArgumentException('You need to specify authentication method!');
        }

        $this->getHttpClient()->authenticate($password);
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (null === $this->httpClient) {
            $this->httpClient = new HttpClient($this->options);
        }

        return $this->httpClient;
    }

    /**
     * @param HttpClientInterface $httpClient
     */
    public function setHttpClient(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Clears used headers.
     */
    public function clearHeaders()
    {
        $this->getHttpClient()->clearHeaders();
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->getHttpClient()->setHeaders($headers);
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    public function getOption($name)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }

        return $this->options[$name];
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     * @throws InvalidArgumentException
     */
    public function setOption($name, $value)
    {
        if (!array_key_exists($name, $this->options)) {
            throw new InvalidArgumentException(sprintf('Undefined option called: "%s"', $name));
        }
        if ('api_version' == $name && !in_array($value, $this->getSupportedApiVersions())) {
            throw new InvalidArgumentException(sprintf('Invalid API version ("%s"), valids are: %s', $name, implode(', ', $supportedApiVersions)));
        }

        if ('api_version' == $name && !in_array($value, $this->getSupportedFormats())) {
            throw new InvalidArgumentException(sprintf('Invalid format ("%s"), valids are: %s', $name, implode(', ', $supportedApiVersions)));
        }

        $this->options[$name] = $value;
    }

    /**
     * Returns an array of valid API versions supported by this client.
     *
     * @return array
     */
    public function getSupportedApiVersions()
    {
        return array('v2');
    }

    /**
     * Returns an array of valid  formats supported by this client.
     *
     * @return array
     */
    public function getSupportedFormats()
    {
        return array('json', 'xml');
    }

    /**
     * Triggered when invoking inaccessible methods in an object context.
     *
     * @param string $name name of the being called
     * @param array  $args parameters
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }
}
