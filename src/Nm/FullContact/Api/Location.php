<?php

namespace Nm\FullContact\Api;

/**
 * Location Api.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class Location extends AbstractApi
{
    const PATH = '/address';

    /**
     * The normalizer method takes semi-structured location data
     * and returns structured location data
     *
     * @param string $place   semi-structured location
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function normalizer($place, array $options = array())
    {
        $options = array_merge(['place' => $place], $options);

        return $this->getData(self::PATH.'/locationNormalizer', $options);
    }

    /**
     * The enrichment method takes semi-structured location data and returns a collection
     * of locations represented by the query.
     *
     * @param string $place   semi-structured location
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function enrichment($place, array $options = array())
    {
        $options = array_merge(['place' => $place], $options);

        return $this->getData(self::PATH.'/locationEnrichment', $options);
    }
}
