<?php

return array(
    'oembed' => array(
        'props'    => array(
            'liststyle' => function($liststyle = null) {
                return $liststyle ?? option('tasinttttttt.oembed.liststyle');
            },
            'value' => function($value = null) {
                return Yaml::decode($value);
            },
        ),
        'computed' => array(),
    ),
);
