<?php

use Hashbangcode\SitemapChecker\Crawler\ChromeCrawler;
use Hashbangcode\SitemapChecker\Url\Url;
use HeadlessChromium\BrowserFactory;

require __DIR__ . '/../vendor/autoload.php';

/**
 * This example creates a Chrome browser, gets the result of a single page and then
 * writes that data to a file.
 */

// Set the engine up.
$crawler = new ChromeCrawler();

// Set the Chrome browser factory.
$browserFactory = new BrowserFactory();
$options = [
    'windowSize' => [1000, 750], // 4:3 standard monitor resolution.
    'noSandbox' => TRUE, // Useful is running in docker environments.
];
$browserFactory->setOptions($options);

// Add the engine to the crawler.
$crawler->setEngine($browserFactory->createBrowser());

// Configure the crawler.
$crawler->setTakeScreenshot(true);
$crawler->setScreenshotType(ChromeCrawler::SCREENSHOT_TYPE_DATA);

// Create a URL.
$url = new Url('https://www.hashbangcode.com/');

// Crawl a single URL.
$result = $crawler->processUrl($url);

// Check if we have screenshot data and if so write it to a file.
if ($crawler->getScreenshotType() == ChromeCrawler::SCREENSHOT_TYPE_DATA) {
    $fileData = $result->getScreenshotFileData();
    if (is_string($fileData) && $fileData !== '') {
        file_put_contents($fileData, $url->generateHash() . '.png');
    }
}

// Print result object.
echo $result;
