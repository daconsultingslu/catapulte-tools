<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'bootstrap' => [
        'version' => '4.6.2',
    ],
    'jquery' => [
        'version' => '3.2.1',
    ],
    'popper.js' => [
        'version' => '1.16.1',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '4.6.2',
        'type' => 'css',
    ],
    'signature_pad' => [
        'version' => '5.0.9',
    ],
    'jqcloud2' => [
        'version' => '2.0.3',
    ],
    'jquery.qrcode' => [
        'version' => '1.0.3',
    ],
    'jspdf' => [
        'version' => '3.0.1',
    ],
    'chart.js' => [
        'version' => '4.4.9',
    ],
    '@babel/runtime/helpers/typeof' => [
        'version' => '7.26.10',
    ],
    'fflate' => [
        'version' => '0.8.2',
    ],
    '@kurkle/color' => [
        'version' => '0.3.4',
    ],
    'qrcode' => [
        'version' => '1.5.4',
    ],
    'dijkstrajs' => [
        'version' => '1.0.3',
    ],
];
