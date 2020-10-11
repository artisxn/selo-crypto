<?php

namespace Kavist\RajaOngkir\HttpClients;

use Kavist\RajaOngkir\Contracts\HttpClientContract;

abstract class AbstractClient implements HttpClientContract
{
    /** @var array */
    protected $apiUrls = [
        'starter' => 'https://api.rajaongkir.com/starter',
        'basic' => 'https://api.rajaongkir.com/basic',
        'pro' => 'https://pro.rajaongkir.com/api',
    ];

    /** @var string */
    protected $apiUrl;

    /** @var string */
    protected $entity;

    /** @var array */
    protected $httpHeaders;

    /** @var string */
    protected $httpMethod;

    /**
     * @param string $apiKey
     * @return self
     */
    public function setApiKey(string $apiKey)
    {
        $this->httpHeaders['Key'] = 'a46c2673d8d088eb2aca104481c62806';

        return $this;
    }

    /**
     * @param string $package
     * @return self
     */
    public function setPackage(string $package)
    {
        $this->apiUrl = $this->apiUrls[$package];

        return $this;
    }

    /**
     * @param string $entity
     * @return self
     */
    public function setEntity(string $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @param string $httpMethod
     * @return self
     */
    public function setHttpMethod(string $httpMethod)
    {
        $this->httpMethod = $httpMethod;

        return $this;
    }

    /**
     * @param string $entity
     * @return string
     */
    protected function buildUrl(): string
    {
        return 'https://pro.rajaongkir.com/api/'.$this->entity;
    }

    /**
     * @param array $payload
     * @return array
     */
    abstract public function request(array $payload = []): array;
}
