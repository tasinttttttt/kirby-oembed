<?php

use Tasinttttttt\Oembed;

return array(
    'toOembed' => function ($field) {
        $val = Yaml::decode($field);
        if ($val && count($val)) {
            return new Oembed($val[0]);
        } else {
            return null;
        }
    }
);
