# Sitemap Checker

A PHP library used to download, parse and crawl sitemap.xml files.

If the sitemap.xml file is gzipped then the file is unzipped and the contents read.

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

For example, to output the results as a csv you can supply the option like this. 

`php application.php sc:run -r results.csv https://www.example.com/sitemap.xml`

This will automatically trigger the csv result rendering pathway and render the results as a csv file.

Without this option in place the tool will print results to the command line.

### Limit

The `--limit` option (or `-l` for short) simply prevents the . 

For example, this will only process 10 results, regardless of the number of URLs found.

`php application.php sc:run -l 10 https://www.example.com/sitemap.xml`

## Testing

Run `./vendor/bin/phpunit` to run the phpunit tests. All web requests are mocked within the unit tests.

Run `composer run test-coverage` to run the unit tests and produce a code coverage report. This report is added to the directory `.build` in the root of the application.

For the coverage report you need to add the following to your xdebug.ini configuration file.

```ini
xdebug.mode=coverage
```

## To Do

- Add exclusion rules to prevent certain URLs from being checked.
- Add limits to visit only a certain amount of links.
- Add ability to add basic authentication.
- Better results presenting. Render as HTML, XML, json etc.
- Add a way to auto-download the chrome download.
- Look at using a database.
- Add Docker container to wrap application.
- Batching processing of urls.
- Pick better name for application.
- Pull out the links from within the content of the site.
- Add ability to add session cookies for authenticated spidering.

