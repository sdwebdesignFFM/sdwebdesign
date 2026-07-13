<?php

namespace Tests\Unit;

use App\Models\Setting;
use PHPUnit\Framework\TestCase;

class SettingTest extends TestCase
{
    public function test_phone_link_strips_all_formatting_except_leading_plus(): void
    {
        $setting = new Setting(['phone' => '+49 152 538 2211 4']);

        $this->assertSame('+4915253822114', $setting->phone_link);
    }

    public function test_formatted_phone_returns_readable_german_format(): void
    {
        $setting = new Setting(['phone' => '+49 152 538 2211 4']);

        $this->assertSame('+49 152 53822114', $setting->formatted_phone);
    }

    public function test_phone_accessors_are_null_without_a_phone_number(): void
    {
        $setting = new Setting;

        $this->assertNull($setting->phone_link);
        $this->assertNull($setting->formatted_phone);
    }

    public function test_formatted_phone_falls_back_to_raw_value_for_non_matching_numbers(): void
    {
        $setting = new Setting(['phone' => '+1 (555) 123-4567']);

        $this->assertSame('+1 (555) 123-4567', $setting->formatted_phone);
        $this->assertSame('+15551234567', $setting->phone_link);
    }
}
