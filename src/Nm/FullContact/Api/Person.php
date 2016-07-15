<?php

namespace Nm\FullContact\Api;

/**
 * Person Api.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class Person extends AbstractApi
{

    const PATH = '/person';

    /**
     * Request more information about a specific person by email
     *
     * @param string $email   email address
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function lookupByEmail($email, array $options = array())
    {
        $options = array_merge(['email' => $email], $options);

        return $this->getData(self::PATH, $options);
    }

    /**
     * Request more information about a specific person by MD5-hashed email address
     *
     * @param string $emailMD5 MD5-hashed email address
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function lookupByEmailMD5($emailMD5, array $options = array())
    {
        $options = array_merge(['emailMD5' => $emailMD5], $options);

        return $this->getData(self::PATH, $options);
    }

    /**
     * Request more information about a specific person by SHA256-hashed email address
     *
     * @param string $emailSHA256 SHA256-hashed email address
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function lookupByEmailSHA256($emailSHA256, array $options = array())
    {
        $options = array_merge(['emailSHA256' => $emailSHA256], $options);

        return $this->getData(self::PATH, $options);
    }

    /**
     * Request more information about a specific person by phone.
     *
     * @param string $phone   phone number
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function lookupByPhone($phone, array $options = array())
    {
        $options = array_merge(['phone' => $phone], $options);

        return $this->getData(self::PATH, $options);
    }

    /**
     * Request more information about a specific person by twitter.
     *
     * @param string $twitter twitter name
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function lookupByTwitter($twitter, array $options = array())
    {
        $options = array_merge(['twitter' => $twitter], $options);

        return $this->getData(self::PATH, $options);
    }
}
