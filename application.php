#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Hashbangcode\SitemapChecker\AppKernel;
use Symfony\Component\Console\Application;

$kernel = new AppKernel('dev', true);
$kernel->boot();

$container = $kernel->getContainer();
$application = $container->get(Application::class);
$application->run();
