<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/Tests')
    ->in(__DIR__ . '/Timeseries');

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->finder($finder);
