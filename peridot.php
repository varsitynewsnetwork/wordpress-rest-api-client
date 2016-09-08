<?php

use Evenement\EventEmitterInterface;

return function (EventEmitterInterface $eventEmitter) {
    $eventEmitter->on('peridot.start', function (\Peridot\Console\Environment $environment) {
        $environment->getDefinition()->getArgument('path')->setDefault('specs');
    });
};

