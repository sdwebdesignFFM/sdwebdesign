<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageSolutionsOrderTest extends TestCase
{
    use RefreshDatabase;

    private function seedHomepage(): void
    {
        Page::factory()->create([
            'type' => Page::TYPE_HOME,
            'slug' => ['de' => 'home', 'en' => 'home'],
            'title' => ['de' => 'Home', 'en' => 'Home'],
            'is_active' => true,
            'content' => [
                'de' => [
                    'hero' => ['title' => 'Hero', 'subtitle' => 'Sub'],
                ],
            ],
        ]);
    }

    public function test_homepage_solutions_section_lists_plattformen_first_then_e_commerce_mobile_websites(): void
    {
        $this->seedHomepage();

        $body = $this->get('/')->assertStatus(200)->getContent();

        $platformenPos = strpos($body, 'Digitale Plattformen &amp; Webanwendungen');
        $eCommercePos = strpos($body, 'E-Commerce &amp; Online-Shops');
        $mobilePos = strpos($body, 'Mobile Anwendungen (iOS / Android / PWA)');
        $websitesPos = strpos($body, 'Unternehmenswebsites mit Substanz');

        $this->assertNotFalse($platformenPos, 'Plattformen accordion missing');
        $this->assertNotFalse($eCommercePos, 'E-Commerce accordion missing');
        $this->assertNotFalse($mobilePos, 'Mobile accordion missing');
        $this->assertNotFalse($websitesPos, 'Websites accordion missing');

        $this->assertLessThan($eCommercePos, $platformenPos, 'Plattformen must appear before E-Commerce');
        $this->assertLessThan($mobilePos, $eCommercePos, 'E-Commerce must appear before Mobile');
        $this->assertLessThan($websitesPos, $mobilePos, 'Mobile must appear before Websites');
    }
}
