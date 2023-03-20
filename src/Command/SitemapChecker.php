<?php

namespace Hashbangcode\SitemapChecker\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Hashbangcode\SitemapChecker\Crawler\GuzzlePromiseCrawler;
use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use Hashbangcode\SitemapChecker\Source\SitemapXmlSource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'sitemap-checker:run',
    description: 'Runs the sitemap checker against a given sitemap.xml file.',
    hidden: false,
    aliases: ['sc:run']
)]
class SitemapChecker extends Command
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected ?Client $client = NULL;

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if ($this->client === NULL) {
            $this->client = new Client();
        }
        return $this->client;
    }

    /**
     * @param Client $client
     * @return SitemapChecker
     */
    public function setClient(Client $client): SitemapChecker
    {
        $this->client = $client;
        return $this;
    }

    protected function configure(): void
    {
        $this->addArgument('sitemap', InputArgument::REQUIRED, 'The sitemap.xml file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $input->getArgument('sitemap');

        $io = new SymfonyStyle($input, $output);

        if (is_string($sitemap) === FALSE || filter_var($sitemap, FILTER_VALIDATE_URL) === FALSE) {
            $io->error('Invalid sitemap URL found.');
            return Command::INVALID;
        }

        $client = $this->getClient();

        $sitemapSource = new SitemapXmlSource($client);
        try {
            $sitemapData = $sitemapSource->fetch($sitemap);
        }
        catch (ClientException $e) {
            $io->error('Unable to download sitemap.xml file data from ' . $sitemap);
            return Command::FAILURE;
        }
        
        $sitemapParser = new SitemapXmlParser();
        $list = $sitemapParser->parse($sitemapData);

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