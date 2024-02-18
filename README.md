# Sitemap Checker

A PHP library used to download, parse and crawl sitemap.xml files.

If the sitemap.xml file is gzipped then the file is unzipped and the contents read.

[![PHP Composer](https://github.com/hashbangcode/sitemap_checker/actions/workflows/php.yml/badge.svg)](https://github.com/hashbangcode/sitemap_checker/actions/workflows/php.yml)

## Installation

Download the PHP files to a directory and run `composer install`. This will set up everything needed for the application to run.

## Usage

To run the application on the command line use the following.

`php application.php sitemap-checker:run https://www.example.com/sitemap.xml`

This will download the sitemap.xml file, let you know how many URLs it detected before starting to crawl them.

You can also use a shorthand to run the same command:

`php application.php sc:run https://www.example.com/sitemap.xml`

Once the crawling has finished the command will print out the results.

## Options

A number of options exist for this tool.

### Result Output

To change what type of result is returned you can supply the `--result-file` option, or `-r` for short.

The following rendering types are available:
- Plain, the default renderer (prints the results line by line).
- CSV
- JSON
- XML
- HTML

For example, to output the results as a csv you can supply the option like this. 

`php application.php sc:run -r results.csv https://www.example.com/sitemap.xml`

This will automatically trigger the csv result rendering pathway and render the results as a csv file.

Without this option in place the tool will print results to the command line using "plain" rendering.

### Limit

The `--limit` option (or `-l` for short) simply prevents the tool from checking any more than this limit. 

For example, this will only process 10 results, regardless of the number of URLs found.

`php application.php sc:run -l 10 https://www.example.com/sitemap.xml`

### Engine

The `--engine` option (or `-e` for short) changes the type of checking engine used.

Options are:
- 'guzzle' : (Default) Run the sitemap checker using Guzzle promises.
- 'chrome' : Run the sitemap checker using headless Chrome. To get this running you'll first need to add the
chrome binary to the location `./chrome/chrome` (i.e. within the package).

For example, to change the sitemap checker engine to use headless Chrome use the following.

`php application.php sc:run -e chrome https://www.example.com/sitemap.xml`

### Exclude

Pass a list of URLs to exclude using the `--exclude` (or `-x` for short) flag. This will prevent URLs from being added
to the collections and checked. This can be a comma separated list of URLs to exclude. Wildcards can also be used to
prevent certain inner URLs from being used.

Some examples:

To prevent the path `https://www.example.com/some-page` being used.

`php application.php sc:run https://www.example.com/ --exclude='https://www.hashbangcode.com/some-page.html'`

To prevent anything in `https://www.example.com/sub-dir1` and `https://www.example.com/sub-dir2` from being used:

`php application.php sc:run https://www.example.com/ --exclude='https://www.example.com/sub-dir1/*,https://www.hashbangcode.com/sub-dir2/*'`

To prevent anything on the external site `https://www.example2.org` being used.

`php application.php sc:run https://www.example.com/ --exclude='https://www.example2.org/*'`

## Testing

Run `./vendor/bin/phpunit` to run the phpunit tests. All web requests are mocked within the unit tests.

Run `composer run test-coverage` to run the unit tests and produce a code coverage report. This report is added to the directory `.build` in the root of the application.

For the coverage report you need to add the following to your xdebug.ini configuration file.

```ini
xdebug.mode=coverage
```

## Example Using Classes

To extract the classes out of this project to use independently do the following.

```php
<?php

use Hashbangcode\SitemapChecker\Crawler\GuzzlePromiseCrawler;

require __DIR__ . '/vendor/autoload.php';

// Set the engine up.
$client = new \GuzzleHttp\Client();
$crawler = new \Hashbangcode\SitemapChecker\Crawler\GuzzleCrawler();
$crawler->setEngine($client);

// Create a URL.
$url = new \Hashbangcode\SitemapChecker\Url\Url('https://www.hashbangcode.com/');

// Crawl a single URL.
$result = $crawler->processUrl($url);

// Print result object.
print_r($result);

```

## To Do

There's still lots to do.

- Add exclusion rules to prevent certain URLs from being checked.
- Better results presenting. Render as HTML, XML, json etc.
- Add a way to auto-download the Chrome binary.
- Look into using a database or message queue.
- Add Docker container to wrap application.
- Batching processing of urls (i.e. don't do everything in one go).
- Pick better name for application.
- Add ability to add session cookies for authenticated spidering.
