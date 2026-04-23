<?php

namespace Tests\Feature;

use App\Http\Middleware\RedirectTrailingSlash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class RedirectTrailingSlashTest extends TestCase
{
    /**
     * Dispatch a request with the given URI through the middleware.
     *
     * We bypass $this->get() because Laravel's test helper trims trailing
     * slashes in prepareUrlForRequest(), which makes it impossible to exercise
     * this middleware via the normal testing API.
     */
    private function dispatch(string $uri, string $method = 'GET'): \Symfony\Component\HttpFoundation\Response
    {
        $request = Request::create('http://localhost'.$uri, $method);
        $middleware = new RedirectTrailingSlash;

        return $middleware->handle($request, fn () => new Response('next', 200));
    }

    public function test_trailing_slash_redirects_with_301(): void
    {
        $response = $this->dispatch('/in/frankfurt-am-main/');

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('http://localhost/in/frankfurt-am-main', $response->headers->get('Location'));
    }

    public function test_root_path_is_not_redirected(): void
    {
        $response = $this->dispatch('/');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('next', $response->getContent());
    }

    public function test_url_without_trailing_slash_is_not_redirected(): void
    {
        $response = $this->dispatch('/in/frankfurt-am-main');

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('next', $response->getContent());
    }

    public function test_query_string_is_preserved_on_redirect(): void
    {
        $response = $this->dispatch('/in/frankfurt-am-main/?utm_source=google&utm_campaign=test');

        $this->assertSame(301, $response->getStatusCode());

        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('http://localhost/in/frankfurt-am-main?', $location);
        $this->assertStringContainsString('utm_source=google', $location);
        $this->assertStringContainsString('utm_campaign=test', $location);
    }

    public function test_nested_path_with_trailing_slash_redirects(): void
    {
        $response = $this->dispatch('/loesungen/websites/');

        $this->assertSame(301, $response->getStatusCode());
        $this->assertSame('http://localhost/loesungen/websites', $response->headers->get('Location'));
    }

    public function test_post_requests_are_not_redirected(): void
    {
        $response = $this->dispatch('/kontakt/', 'POST');

        $this->assertNotSame(301, $response->getStatusCode());
    }
}
