<?php

namespace Hashbangcode\SitemapChecker\Command;

use GuzzleHttp\Client;
use Hashbangcode\SitemapChecker\Crawler\GuzzlePromiseCrawler;
use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use Hashbangcode\SitemapChecker\Source\SitemapXmlSource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'sitemap-checker:run',
    description: 'Runs the sitemap checker against a given sitemap.xml file.',
    hidden: false,
    aliases: ['sc:run']
)]
class SitemapChecker extends Command
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('sitemap', InputArgument::REQUIRED, 'The sitemap.xml file.');
        $this->addArgument('client', InputArgument::OPTIONAL, 'The http client.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $input->getArgument('sitemap');
        $client = $input->getArgument('client');

        if (!$client) {
            $client = new Client();
        }

        $sitemapSource = new SitemapXmlSource($client);
        $sitemapParser = new SitemapXmlParser();
        $list = $sitemapParser->parse($sitemapSource->fetch($sitemap));

        $output->writeln($list->count() . ' URLs found, beginning processing.');

        $crawler = new GuzzlePromiseCrawler();
        $crawler->setEngine($client);
        $results = $crawler->crawl($list);

        foreach ($results as $result) {
            $output->writeln($result->getUrl()->getRawUrl() . ' ' . $result->getResponseCode());
        }

        return Command::SUCCESS;
    }

}