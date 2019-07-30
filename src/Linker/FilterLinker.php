<?php

declare(strict_types=1);

namespace App\Linker;

use Symfony\Component\DomCrawler\Crawler;

class FilterLinker
{
    /**
     * @var string
     */
    private $link;
    /**
     * @var string
     */
    private $keyWord;
    /**
     * @var string
     */
    private $attribute;

    /**
     * FilterLinker constructor.
     * @param string $attribute
     * @param string $link
     * @param string|null $keyWord
     */
    public function __construct(string $attribute, string $link, string $keyWord = null)
    {
        $this->link = $link;
        $this->keyWord = $keyWord;
        $this->attribute = $attribute;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * @return string
     */
    public function getKeyWord(): ?string
    {
        return $this->keyWord;
    }

    public function filter(Crawler $crawler): array
    {
        $links = [];

        foreach ($crawler->filter($this->attribute) as $link) {
            // if only link search
            if ($this->getKeyWord() === null) {
                if ($this->getLink() === $link->getAttribute('href')) {
                    $links['link'] = $link->getAttribute('href');
                    return $links;
                }
            }

            if ($this->getKeyWord() === strtolower($link->nodeValue)) {
                $links['keyWord'] = $link->nodeValue;
                $links['rel'] = $link->getAttribute('rel');

                if ($this->getLink() === $link->getAttribute('href')) {
                    $links['link'] = $link->getAttribute('href');
                }
            }
        }

        return sizeof($links) === 3 ? $links : [];
    }
}
