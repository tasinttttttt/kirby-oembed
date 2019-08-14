<?php

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('tasinttttttt/oembed', array(
    'options' => array(
        'liststyle' => 'table',
        'log.enabled' => false,
        'log' => function (string $msg, string $level = 'info', array $context = []):bool {
            if (option('tasinttttttt.oembed.log.enabled') && function_exists('kirbyLog')) {
                kirbyLog('tasinttttttt.oembed.log')->log($msg, $level, $context);
                return true;
            }
            return false;
        },
        'oembedApis' => [
            'soundcloud' => "https://soundcloud.com/oembed",
            'twitch' => "https://api.twitch.tv/v5/oembed",
            'vimeo' => "https://vimeo.com/api/oembed.json",
            'youtube' => "https://youtube.com/oembed",
        ],
        'debug' => option('debug'),
        'expires' => (60 * 24) * 4, // in minutes
        'cache' => true,
    ),
    'fields'       => require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib/fields.php',
    'fieldMethods' => require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib/fieldMethods.php',
    'translations' => array(
        'en' => require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib/languages/en.php',
        'fr' => require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib/languages/fr.php',
    ),
    'api' => require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib/api.php'
));
