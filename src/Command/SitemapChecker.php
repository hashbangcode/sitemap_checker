<?php

namespace Hashbangcode\SitemapChecker\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Hashbangcode\SitemapChecker\Crawler\ChromeCrawler;
use Hashbangcode\SitemapChecker\Crawler\GuzzleCrawler;
use Hashbangcode\SitemapChecker\Crawler\GuzzlePromiseCrawler;
use Hashbangcode\SitemapChecker\Parser\SitemapIndexXmlParser;
use Hashbangcode\SitemapChecker\Parser\SitemapXmlParser;
use Hashbangcode\SitemapChecker\Result\ResultCollection;
use Hashbangcode\SitemapChecker\ResultRender\CsvResultRender;
use Hashbangcode\SitemapChecker\ResultRender\JsonResultRender;
use Hashbangcode\SitemapChecker\ResultRender\PlainResultRender;
use Hashbangcode\SitemapChecker\ResultRender\HtmlResultRender;
use Hashbangcode\SitemapChecker\ResultRender\XmlResultRender;
use Hashbangcode\SitemapChecker\RobotsTxtParser;
use Hashbangcode\SitemapChecker\Source\RobotsTxtSource;
use Hashbangcode\SitemapChecker\Source\SitemapXmlSource;
use Hashbangcode\SitemapChecker\Url\UrlCollection;
use HeadlessChromium\BrowserFactory;
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
        $this->addOption('limit', 'l',  InputOption::VALUE_OPTIONAL, 'Limit the number of URLs polled.', -1);
        $this->addOption('engine', 'e',  InputOption::VALUE_OPTIONAL, 'The engine to use, defaults to guzzle.', 'guzzle');
        $this->addOption('exclude', 'x', InputOption::VALUE_OPTIONAL, 'A set of URLs to exclude.');
        $this->addOption('robots', 't', InputOption::VALUE_OPTIONAL, 'A robots.txt file to download and use as exclusion fules.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $input->getArgument('sitemap');
        $limit = $input->getOption('limit');
        if (is_numeric($limit)) {
          $limit = (int) $limit;
        }
        $engine = $input->getOption('engine');

        $exclude = $input->getOption('exclude');
        if (is_string($exclude)) {
          $exclude = array_filter(explode(',', $exclude));
        }
        else {
          $exclude = [];
        }

        $io = new SymfonyStyle($input, $output);

        $robots = $input->getOption('robots');

        if (is_string($sitemap) === FALSE || filter_var($sitemap, FILTER_VALIDATE_URL) === FALSE) {
            $io->error('Invalid sitemap URL found.');
            return Command::INVALID;
        }

        // Allow for non-sitemap.xml URLs to be passed.
        if (str_contains($sitemap, 'sitemap.xml') === FALSE) {
          if (substr($sitemap, -1) === '/') {
            $sitemap = substr($sitemap, 0, -1);
          }
          $sitemap .= '/sitemap.xml';
        }

        $client = $this->getClient();

        // Include the robots.txt file exclusion rules.
        if (is_string($robots)) {
            if (filter_var($sitemap, FILTER_VALIDATE_URL) === FALSE) {
                $io->error('Invalid robots.txt URL passed.');
                return Command::INVALID;
            }
            $robotsTxtSource = new RobotsTxtSource($client);
            $robotsTxt = $robotsTxtSource->fetch($robots);
            $robotsTxtParse = new RobotsTxtParser();
            $robotsTxtRules = $robotsTxtParse->parse($robotsTxt, str_replace('/sitemap.xml', '', $sitemap));
            $exclude = array_merge($exclude, $robotsTxtRules);
        }

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

          if (count($exclude) > 0) {
            $list->setExclusionRules($exclude);
          }

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
          $list = $sitemapParser->parse($sitemapData, $exclude);
        }

        if ($list->count() === 0) {
          $output->writeln('No URLs found.');
          return Command::SUCCESS;
        }

        if ($limit !== -1) {
          $output->writeln('Limiting to ' . $limit);
          if (is_int($limit) === TRUE) {
            $listChunks = $list->chunk($limit);
            $list = $listChunks[0];
          }
        }

        $listChunks = $list->chunk($this->chunkLength);
        $output->writeln($list->count() . ' URLs found, beginning processing.');

        switch ($engine) {
          case 'chrome':
            $crawler = new ChromeCrawler();
            $browserFactory = new BrowserFactory('./chrome/chrome');
            $crawler->setEngine($browserFactory->createBrowser());
            break;

          case 'guzzle':
          default:
            $crawler = new GuzzlePromiseCrawler();
            $crawler->setEngine($client);
            break;
        }

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
          // Write a blank line to print the results correctly.
          $resultRender = new PlainResultRender();
          $output->writeln('');
          $output->writeln($resultRender->render($results));
        }
        elseif (is_string($resultFile) && str_contains($resultFile, '.csv')) {
          $io->info('Writing CSV file.');
          $resultRender = new CsvResultRender();
          $renderedResult = $resultRender->render($results);
          file_put_contents($resultFile, $renderedResult);
        }
        elseif (is_string($resultFile) && str_contains($resultFile, '.json')) {
          $io->info('Writing JSON file.');
          $resultRender = new JsonResultRender();
          $renderedResult = $resultRender->render($results);
          file_put_contents($resultFile, $renderedResult);
        }
        elseif (is_string($resultFile) && str_contains($resultFile, '.xml')) {
          $io->info('Writing XML file.');
          $resultRender = new XmlResultRender();
          $renderedResult = $resultRender->render($results);
          file_put_contents($resultFile, $renderedResult);
        }
        elseif (is_string($resultFile) && str_contains($resultFile, '.html')) {
          $io->info('Writing HTML file.');
          $resultRender = new HtmlResultRender();
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