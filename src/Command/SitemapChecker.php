<?php

namespace Hashbangcode\SitemapChecker\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Hashbangcode\SitemapChecker\Crawler\GuzzlePromiseCrawler;
use Hashbangcode\SitemapChecker\Parser\SitemapIndexXmlParser;
use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\ResultRender\CsvResultRender;
use Hashbangcode\SitemapChecker\Source\SitemapXmlSource;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected int $chunkLength = 50;

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
        $this->addOption('result-file', 'r',  InputOption::VALUE_OPTIONAL, 'The output file.');
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

        if ($sitemapSource->isSitemapIndex()) {
          $io->info('Sitemap index file found, parsing contents.');
          $sitemapIndexXmlParse = new SitemapIndexXmlParser();
          $sitemapList = $sitemapIndexXmlParse->parse($sitemapData);

          $list = new UrlCollection();

          foreach ($sitemapList as $sitemapUrl) {
            $sitemapData = $sitemapSource->fetch($sitemapUrl->getRawUrl());
            $sitemapParser = new SitemapXmlParser();
            $urlList = $sitemapParser->parse($sitemapData);
            foreach ($urlList as $url) {
              $list->add($url);
            }
          }
        } else {
          $sitemapParser = new SitemapXmlParser();
          $list = $sitemapParser->parse($sitemapData);
        }

        $listChunks = $list->chunk($this->chunkLength);
        $output->writeln($list->count() . ' URLs found, beginning processing.');

        $crawler = new GuzzlePromiseCrawler();
        $crawler->setEngine($client);

        $results = new ResultCollection();

        $progressBar = new ProgressBar($output, $list->count());
        $progressBar->setFormat('normal');
        $progressBar->start();

        foreach ($listChunks as $listChunk) {
          $result = $crawler->crawl($listChunk);
          $progressBar->advance($this->chunkLength);
          foreach ($result as $res) {
            $results->add($res);
          }
        }

        $progressBar->finish();

        $resultFile = $input->getOption('result-file');

        if ($resultFile === NULL) {
          foreach ($results as $result) {
              $output->writeln($result->getUrl()->getRawUrl() . ' ' . $result->getResponseCode());
          }
        }
        elseif (str_contains($resultFile, '.csv')) {
          $io->info('Writing CSV file.');
          $resultRender = new CsvResultRender();
          $renderedResult = $resultRender->render($results);
          file_put_contents($resultFile, $renderedResult);
        }
        else {
          $io->error('Invalid output format found.');
          return Command::INVALID;
        }

        return Command::SUCCESS;
    }

}