<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Register an additional JSON-LD entity to be rendered as its own script tag
     * in the frontend layout (alongside the primary seotools JsonLd entity).
     *
     * @param  array<string, mixed>  $schema
     */
    protected function addSchema(array $schema): void
    {
        $schemas = view()->shared('pageSchemas', []);
        $schemas[] = $schema;
        view()->share('pageSchemas', $schemas);
    }

    /**
     * Register a BreadcrumbList schema from an ordered list of crumbs.
     *
     * @param  array<int, array{name: string, url: string}>  $crumbs
     */
    protected function addBreadcrumbSchema(array $crumbs): void
    {
        $items = [];

        foreach (array_values($crumbs) as $index => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ];
        }

        $this->addSchema([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ]);
    }
}
