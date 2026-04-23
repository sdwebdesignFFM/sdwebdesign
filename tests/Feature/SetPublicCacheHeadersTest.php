<?php

namespace Tests\Feature;

use App\Http\Middleware\SetPublicCacheHeaders;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class SetPublicCacheHeadersTest extends TestCase
{
    /**
     * Invoke the middleware in isolation. Laravel's full test stack pre-sets
     * session cache headers that mask our override; going through the raw
     * middleware shows its contribution in isolation.
     */
    private function dispatch(string $path, string $method = 'GET', int $status = 200, ?string $cacheControlFromApp = null): Response
    {
        $request = Request::create('http://localhost'.$path, $method);
        $middleware = new SetPublicCacheHeaders;

        return $middleware->handle($request, function () use ($status, $cacheControlFromApp) {
            $response = new Response('body', $status);
            if ($cacheControlFromApp !== null) {
                $response->headers->set('Cache-Control', $cacheControlFromApp);
            }

            return $response;
        });
    }

    public function test_public_pages_get_public_cache_control(): void
    {
        $response = $this->dispatch('/in/frankfurt-am-main');

        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('public', $cacheControl);
        $this->assertStringContainsString('max-age=300', $cacheControl);
        $this->assertStringContainsString('s-maxage=900', $cacheControl);
    }

    public function test_homepage_gets_public_cache_control(): void
    {
        $response = $this->dispatch('/');

        $this->assertStringContainsString('public', $response->headers->get('Cache-Control'));
    }

    public function test_admin_routes_are_not_cached_publicly(): void
    {
        $response = $this->dispatch('/admin/pages', 'GET', 200, 'no-store, private');

        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }

    public function test_kontakt_form_route_is_not_cached_publicly(): void
    {
        $response = $this->dispatch('/kontakt', 'GET', 200, 'no-store, private');

        // Form pages keep their original no-store header — a cached CSRF token
        // would cause token mismatches on submit.
        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }

    public function test_quote_token_routes_are_not_cached(): void
    {
        $response = $this->dispatch('/angebot/sometoken', 'GET', 200, 'no-store, private');

        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }

    public function test_post_requests_are_never_cached(): void
    {
        $response = $this->dispatch('/any-path', 'POST', 200, 'no-store, private');

        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }

    public function test_non_200_responses_keep_default_headers(): void
    {
        $response = $this->dispatch('/nonexistent', 'GET', 404, 'no-store, private');

        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }

    public function test_livewire_endpoint_is_not_publicly_cached(): void
    {
        $response = $this->dispatch('/livewire/update', 'GET', 200, 'no-store, private');

        $this->assertSame('no-store, private', $response->headers->get('Cache-Control'));
    }
}
