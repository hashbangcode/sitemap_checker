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
            // The sitemap file itself.
            new Response(200, ['Content-Type' => 'application/xml'], $sitemapXml),
            // The first item in the sitemap file.
            new Response(200, ['Content-Type' => 'application/xml'], ''),
            // The second item in the sitemap file.
            new Response(200, ['Content-Type' => 'application/xml'], ''),
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

    public function testSitemapCommandCanCreateClient() {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $application = $container->get(Application::class);

        $command = $application->find('sitemap-checker:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'sitemap' => 'https://www.example.com/sitemap.xml'
        ]);
        $this->assertInstanceOf(Client::class, $command->getClient());
    }

    public function testSitemapInitialUrlIsInvalid() {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $application = $container->get(Application::class);

        $command = $application->find('sitemap-checker:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'sitemap' => 'digfyfdguojs'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Invalid sitemap URL found.', $output);

        $this->assertEquals(Command::INVALID, $commandTester->getStatusCode());
    }

    public function testSitemapInitialLinkIsNotSitemap() {
        $sitemapXml = realpath(__DIR__ . '/../data/sitemap.xml');
        $sitemapXml = file_get_contents($sitemapXml);

        $mock = new MockHandler([
            // The sitemap file itself.
            new Response(200, ['Content-Type' => 'application/xml'], $sitemapXml),
            // The first item in the sitemap file.
            new Response(200, ['Content-Type' => 'application/xml'], ''),
            // The second item in the sitemap file.
            new Response(200, ['Content-Type' => 'application/xml'], ''),
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
            'sitemap' => 'https://www.example.com/'
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('2 URLs found, beginning processing.', $output);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }

}