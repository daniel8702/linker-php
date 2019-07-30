<?php

declare(strict_types=1);

namespace App\Linker;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class AdvanceLinker
{
    /**
     * @var string
     */
    private $keyWord;

    /**
     * @var string
     */
    private $link;

    /**
     * @var SimpleLinker
     */
    private $simpleLinker;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Crawler
     */
    protected $crawel;
    /**
     * @var FilterLinker
     */
    private $filterLinker;

    /**
     * KeyWordLinker constructor.
     * @param SimpleLinker $simpleLinker
     * @param FilterLinker $filterLinker
     */
    public function __construct(SimpleLinker $simpleLinker, FilterLinker $filterLinker)
    {
        $this->simpleLinker = $simpleLinker;
        $this->filterLinker = $filterLinker;
        $this->client = new Client();
    }

    public function request(): Crawler
    {
        $this->client->followRedirects();
        $guzzleClient = $this->simpleLinker->getClient();
        $this->client->setClient($guzzleClient);
        $this->crawel = $this->client->request($this->getMethod(), $this->getUrl());

        return $this->crawel;
    }

    public function filter() : array
    {
        return $this->filterLinker->filter($this->crawel);
    }

    public function getMethod(): string
    {
        return $this->simpleLinker->getMethod();
    }

    public function getUrl(): string
    {
        return $this->simpleLinker->getUrl();
    }
    /**
     * @return mixed
     */
    public function getKeyWord(): string
    {
        return strtolower($this->keyWord);
    }

    /**
     * @return mixed
     */
    public function getLink(): string
    {
        return $this->link;
    }

}
