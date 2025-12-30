<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => 'SD Webdesign',
            'titleBefore'  => false,
            'description'  => 'Professionelles Webdesign und Webentwicklung - Ihre Business Website',
            'separator'    => ' | ',
            'keywords'     => ['webdesign', 'webentwicklung', 'business website', 'laravel'],
            'canonical'    => 'full',
            'robots'       => 'index, follow',
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'SD Webdesign',
            'description' => 'Professionelles Webdesign und Webentwicklung - Ihre Business Website',
            'url'         => null,
            'type'        => 'website',
            'site_name'   => 'SD Webdesign',
            'images'      => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            //'site'        => '@sdwebdesign',
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => 'SD Webdesign',
            'description' => 'Professionelles Webdesign und Webentwicklung - Ihre Business Website',
            'url'         => 'full',
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
