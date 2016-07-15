<?php

namespace Nm\FullContact\Api;

/**
 * Name Api.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class Name extends AbstractApi
{
    const PATH = '/name';

    /**
     *  Normalizer method takes quasi-structured name data provided
     *  as a string and outputs the data in a structured manner.
     *  The name string which can include standard prefix, first name, nickname, middle name, last name and suffix.
     *
     * @param string $name    full name
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function normalizer($name, array $options = array())
    {
        $options = array_merge(['q' => $name], $options);

        return $this->getData(self::PATH.'/normalizer', $options);
    }

    /**
     * The deducerByEmail method takes an email address provided as a string
     *  and attempts to deduce a structured name.
     *
     * @param string $email   email address
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function deducerByEmail($email, array $options = array())
    {
        $options = array_merge(['email' => $email], $options);

        return $this->getData(self::PATH.'/deducer', $options);
    }

    /**
     * The deducerByUsername method takes a username provided as a string
     *  and attempts to deduce a structured name.
     *
     * @param string $username
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function deducerByUsername($username, array $options = array())
    {
        $options = array_merge(['username' => $username], $options);

        return $this->getData(self::PATH.'/deducer', $options);
    }

    /**
     * The similarity method compares two names and returns a score indicating how similar they are.
     *
     * @param string $name1
     * @param string $name2
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function similarity($name1, $name2, array $options = array())
    {
        $options = array_merge(['q1' => $name1, 'q2' => $name2], $options);

        return $this->getData(self::PATH.'/similarity', $options);
    }

    /**
     * The stats method used to determine more about a name.
     * This method use name parameter when we are uncertain whether it is the given name or family name.
     *
     * @param string $name
     * @param array  $options
     *
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function stats($name, array $options = array())
    {
        $options = array_merge(['name' => $name], $options);

        return $this->getData(self::PATH.'/stats', $options);
    }

    /**
     * The statsByGivenName method used to determine more about a first name.
     *
     * @param string $name    first name
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function statsByGivenName($name, array $options = array())
    {
        $options = array_merge(['givenName' => $name], $options);

        return $this->getData(self::PATH.'/stats', $options);
    }

    /**
     * The statsByFamilyName method used to determine more about a last name.
     *
     * @param string $name    last name
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function statsByFamilyName($name, array $options = array())
    {
        $options = array_merge(['familyName' => $name], $options);

        return $this->getData(self::PATH.'/stats', $options);
    }

    /**
     * The statsByGivenNameAndFamilyName method used when we know both the first name and last name of the person.
     *
     * @param string $givenName  first name
     * @param string $familyName last name
     * @param array  $options
     *
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function statsByGivenNameAndFamilyName($givenName, $familyName, array $options = array())
    {
        $options = array_merge(['givenName' => $givenName, 'familyName' => $familyName], $options);

        return $this->getData(self::PATH.'/stats', $options);
    }

    /**
     * The parser method parsers a name to first name and last name.
     *
     * @param string $name
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function parser($name, array $options = array())
    {
        $options = array_merge(['q' => $name], $options);

        return $this->getData(self::PATH.'/deducer', $options);
    }
}
