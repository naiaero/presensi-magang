<?php

namespace Tests\Feature;

use Tests\TestCase;

class TimezoneTest extends TestCase
{
    public function test_application_timezone_is_set_to_asia_makassar(): void
    {
        $this->assertSame('Asia/Makassar', config('app.timezone'));
    }
}
