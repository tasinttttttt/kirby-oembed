<?php

use Tasinttttttt\Oembed;

return array(
    'routes' => [
        [
            'pattern' => 'oembed/preview',
            'method' => 'POST',
            'action'  => function () {
                try {
                    $input = get('url');
                    $url = $input && is_string($input) ? $input : '';
                    return [
                        'code' => 200,
                        'status' => 'ok',
                        'data' => Oembed::getOEmbedJsonByUrl($url)
                    ];
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage(), 400);
                }
            }
        ]
    ]
);
