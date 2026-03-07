<?php

namespace Hashbangcode\SitemapChecker\Test\Url;

use Hashbangcode\SitemapChecker\Url\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{

    public function testLinkContructorAcceptsCorrectParameters()
    {
        $link = new Url('https://www.example.com:8000/inner-path?query=123');
        $this->assertEquals('https://www.example.com:8000/inner-path?query=123', $link->getRawUrl());
        $this->assertEquals('https', $link->getScheme());
        $this->assertEquals('www.example.com', $link->getHost());
        $this->assertEquals(8000, $link->getPort());
        $this->assertEquals('/inner-path', $link->getPath());
        $this->assertEquals('query=123', $link->getQuery());
    }

    public function testLinkConstructorAcceptsEmptyUrlString()
    {
        $link = new Url('');
        $this->assertEquals('', $link->getRawUrl());
        $this->assertEquals('', $link->getScheme());
        $this->assertEquals('', $link->getHost());
        $this->assertEquals(null, $link->getPort());
        $this->assertEquals('', $link->getPath());
        $this->assertEquals('', $link->getQuery());
    }

    /**
     * @dataProvider rebuildUrlDataProvider
     */
    public function testRebuildUrl($inputUrl, $result)
    {
        $link = new Url($inputUrl);
        $urlString = $link->rebuildUrl();
        $this->assertEquals($result, $urlString);
    }

    public static function rebuildUrlDataProvider()
    {
        return [
            ['https://www.example.com', 'https://www.example.com/',],
            ['https://www.example.com/', 'https://www.example.com/',],
            ['http://www.example.com/', 'http://www.example.com/',],
            ['https://www.example.com:8080', 'https://www.example.com:8080/',],
            ['https://www.example.com:8080/', 'https://www.example.com:8080/',],
            ['https://www.example.com/testing?query=123', 'https://www.example.com/testing?query=123',],
            ['https://www.example.com/testing?query=123&query2=456', 'https://www.example.com/testing?query=123&query2=456',],
        ];
    }

    public function testUrlHashIsGenerated()
    {
        $link = new Url('http://www.example.com');
        $hash = $link->generateHash();
        $this->assertIsString($hash);
        $this->assertEquals('89e6a0649e06d83370cdf2cbfb05f363934a8d0c', $hash);
    }

    public function testSameUrlHashTheSame()
    {
        $link1 = new Url('http://www.example.com');
        $hash1 = $link1->generateHash();

        $link2 = new Url('http://www.example.com');
        $hash2 = $link2->generateHash();

        $this->assertEquals($link1->rebuildUrl(), $link2->rebuildUrl());
        $this->assertEquals($hash1, $hash2);
    }

}