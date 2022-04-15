<?php

declare(strict_types=1);

namespace Perpetuum\KeksPay\Model\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\RequestOptions;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Perpetuum\KeksPay\Model\Client\Request\RequestInterface;
use Perpetuum\KeksPay\Model\Client\Response\ResponseInterface;
use Perpetuum\KeksPay\Model\Client\Response\ResponseInterfaceFactory;
use Perpetuum\KeksPay\Model\Configuration;
use Exception;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Client implements ClientInterface
{
    public const REQUEST_AFTER_EVENT_NAME = 'perpetuum_kekspay_request_after';
    public const REQUEST_BEFORE_EVENT_NAME = 'perpetuum_kekspay_request_before';

    private $request;

    /**
     * @var ResponseInterfaceFactory
     */
    private $responseInterfaceFactory;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * Client constructor.
     * @param ResponseInterfaceFactory $responseInterfaceFactory
     * @param Configuration $configuration
     * @param ManagerInterface $manager
     */
    public function __construct(
        ResponseInterfaceFactory $responseInterfaceFactory,
        Configuration $configuration,
        ManagerInterface $manager
    ) {
        $this->responseInterfaceFactory = $responseInterfaceFactory;
        $this->configuration = $configuration;
        $this->manager = $manager;
    }

    /**
     * @inheritDoc
     * @SuppressWarnings(CyclomaticComplexity)
     * @SuppressWarnings(StaticAccess)
     */
    public function doRequest(RequestInterface $request): ResponseInterface
    {
        $this->request = $request;

        $uri = ($this->configuration->isSandbox())
            ? $this->configuration->sandboxUrl()
            : $this->configuration->productionUrl();

        try {
            $headers = $this->getBasicHeaders();

            (!empty($request->getHeaders()))
                ? $request->setHeaders(array_merge($request->getHeaders(), $headers))
                : $request->setHeaders($headers);

            $payload = $request->getPayload();

            if ($payload && $payload instanceof DataObject) {
                $payload = $payload->getData();
            } elseif (!$payload) {
                $payload = [];
            } elseif (!is_array($payload)) {
                $payload = [$payload];
            }

            $request
                ->setPayload($payload)
                ->setFullUri(\mb_strtolower($uri . $request->getEndpoint()));

            $this->dispatchRequestEvent($request);

            $handlerStack = HandlerStack::create();

            $middleWare = $request->getMiddleware();
            if ($middleWare) {
                $middleWare($handlerStack, $request);
            }

            $client = $this->factory([
                'headers' => $request->getHeaders(),
                'base_uri' => $uri,
                'handler' => $handlerStack
            ]);

            $response = $client
                ->request(
                    $request->getMethod(),
                    $request->getEndpoint(),
                    ($payload && !empty($payload))
                        ? [RequestOptions::JSON => $payload]
                        : []
                );

            $newResponse = $this
                ->responseInterfaceFactory
                ->create(
                    [
                        'payload' => json_decode($response->getBody()->getContents(), true),
                        'status' => ResponseInterface::STATUS_SUCCESS,
                        'httpStatus' => $response->getStatusCode(),
                        'psrResponse' => $response,
                    ]
                );

            $this->dispatchResponseEvent($newResponse);

            return $newResponse;
        } catch (GuzzleException | Exception $e) {
            $exceptionResponse = $this
                ->responseInterfaceFactory
                ->create(
                    [
                        'information' => $e->getMessage() . ' at ' . $e->getLine() . ' in ' . $e->getFile(),
                        'status' => ResponseInterface::STATUS_EXCEPTION,
                        'httpStatus' => ($e instanceof GuzzleException && $e->getResponse())
                        ? (int) $e->getResponse()->getStatusCode() : null,
                        'psrResponse' => ($e instanceof GuzzleException && $e->getResponse())
                        ? $e->getResponse() : null,
                    ]
                );

            $this->dispatchResponseEvent($exceptionResponse);

            return $exceptionResponse;
        }
    }

    /**
     * @param array $options
     * @return GuzzleClient
     */
    public function factory(array $options = [])
    {
        return new GuzzleClient($options);
    }

    /**
     * @return array
     */
    private function getBasicHeaders()
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * @param $request
     */
    private function dispatchRequestEvent($request)
    {
        $this
            ->manager
            ->dispatch(
                self::REQUEST_BEFORE_EVENT_NAME,
                [
                    'request' => $request
                ]
            );
    }

    /**
     * @param $response
     */
    private function dispatchResponseEvent($response)
    {
        $this
            ->manager
            ->dispatch(
                self::REQUEST_AFTER_EVENT_NAME,
                [
                    'response' => $response
                ]
            );
    }
}
