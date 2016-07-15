<?php

namespace Nm\FullContact\Api;

/**
 * Company Api.
 *
 * @author Mohammed Rhamnia <mohammed.rhamnia@gmail.com>
 */
class Company extends AbstractApi
{

    const PATH = '/company';

    /**
     * Request more information about a specific company by domain
     *
     * @param string $domain  domain of the company being looked up. For example, fullcontact.com
     * @param array  $options
     * @return array|\Guzzle\Http\EntityBodyInterface|mixed|string
     */
    public function lookupByDomain($domain, array $options = array())
    {
        $options = array_merge(['domain' => $domain], $options);

        return $this->getData(self::PATH.'/lookup', $options);
    }
}
