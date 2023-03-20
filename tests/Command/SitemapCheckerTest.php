<?php

namespace Hashbangcode\SitemapChecker\Test\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;

class SitemapCheckerTest extends KernelTestCase
{
    public function testRunSitemap()
    {
        $sitemapXml = realpath(__DIR__ . '/../data/sitemap.xml');
        $sitemapXml = file_get_contents($sitemapXml);

        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/xml'], $sitemapXml),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);

        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $application = $container->get(Application::class);

        $command = $application->find('sitemap-checker:run');
        $command->setClient($httpClient);
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'sitemap' => 'https://www.example.com/sitemap.xml'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('2 URLs found, beginning processing.', $output);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }
}