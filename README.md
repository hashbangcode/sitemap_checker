# Sitemap Checker

A PHP library used to dowload, parse and crawl sitemap.xml files.

If the sitemap.xml file is gzipped then the file is unzipped and the contents read.

## Installation

Download the PHP files to a directory and run `composer install`. This will set up everything needed for the application to run.

## Usage

To run the application on the command line use the following.

`php application.php sitemap-checker:run:run https://www.example.com/sitemap.xml`

This will download the sitemap.xml file, let you know how many URLs it detected before starting to crawl them.

You can also use a shorthand to run the same command:

`php application.php sc:run https://www.example.com/sitemap.xml`

Once the crawling has finished the command will print out the results.

## Testing

Run `./vendor/bin/phpunit` to run the phpunit tests. All web requests are mocked within the unit tests.

Run `composer run test-coverage` to run the unit tests and produce a code coverage report. This report is added to the directory `.build` in the root of the application.

For the coverage report you need to add the following to your xdebug.ini configuration file.

```ini
xdebug.mode=coverage
```

## To Do

- Better results presenting. Render as HTML, XML, json etc.
- Incorporate the chrome library into the crawler.
- Look at using a database.
- Add Docker container to wrap application.
- Batching processing of urls.
- Pick better name for application.

