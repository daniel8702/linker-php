<?php

declare(strict_types=1);

namespace App\Linker;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RedirectMiddleware;
use Psr\Http\Message\ResponseInterface;

final class SimpleLinker
{
    const CONFIG = ['allow_redirects' => ['track_redirects' => true]];
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $url;
    /**
     * @var ResponseInterface
     */
    protected $response;
    /**
     * @var Client
     */
    protected $client;

    /**
     * Linker constructor.
     * @param string $method
     * @param string $url
     * @throws \Exception
     */
    public function __construct(string $method, string $url)
    {
        $url = strpos($url, 'http') !== 0 ? "http://" . $url . '/' : $url . '/';

        $this->method = $method;
        $this->url = $url;
        $this->client = new Client(self::CONFIG);
    }

    /**
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     */
    public function request() : ResponseInterface
    {
        $this->response = $this->client->request($this->getMethod(), $this->getUrl());
        return $this->response;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->getResponse()->getStatusCode();
    }

    /**
     * @return mixed
     */
    public function getResponse(): ResponseInterface
    {
        if (!$this->client) {
            return null;
        }

        return $this->response;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return bool
     */
    public function isRedirect():bool
    {
        $redirect = $this->getRedirect();
        if (empty($redirect[1])) {
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getRedirect(): array
    {
        return $this->response->getHeader(RedirectMiddleware::HISTORY_HEADER);
    }

    /**
     * @return string
     */
    public function getRedirectFrom(): string
    {
        return $this->getRedirect()[0];
    }

    /**
     * @return string
     */
    public function getRedirectTo(): string
    {
        return $this->getRedirect()[1];
    }
}
