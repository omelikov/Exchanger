<?php

namespace Exchanger\CurrencyExchangeBundle\Service\Currency\Sources;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractCryptoCompareSource
 */
abstract class AbstractCryptoCompareSource implements CurrencySourceInterface
{
    /**
     * @var string
     */
    protected $apiUrl;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var MessageFactory
     */
    protected $httpMessageFactory;

    /**
     * AbstractCryptoCompareSource constructor
     *
     * @param HttpClient     $httpClient
     * @param MessageFactory $httpMessageFactory
     * @param string         $apiUrl
     */
    public function __construct(HttpClient $httpClient, MessageFactory $httpMessageFactory, string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        $this->httpClient = $httpClient;
        $this->httpMessageFactory = $httpMessageFactory;
    }

    /**
     * @return float
     *
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function getRate(): float
    {
        // Make request
        $request = $this->httpMessageFactory->createRequest('GET', $this->apiUrl);

        // Get content
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new \Exception("{$this->apiUrl} return bad response");
        }

        $json = json_decode($response->getBody()->getContents(), true);

        if (!$this->jsonIsValidate($json)) {
            throw new \Exception("{$this->apiUrl} return not valid JSON");
        }

        return $this->getRateFromJson($json);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * @param array $json
     *
     * @return float
     */
    protected function getRateFromJson(array $json): float
    {
        return $json[$this->getCode()];
    }

    /**
     * @param array $json
     *
     * @return bool
     */
    protected function jsonIsValidate(array $json): bool
    {
        if (!isset($json[$this->getCode()])) {
            return false;
        }

        $value = (float) $json[$this->getCode()];
        if ($value <= 0) {
            return false;
        }

        return true;
    }
}
