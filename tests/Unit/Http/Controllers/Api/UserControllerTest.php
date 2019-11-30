<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * Test all users GET API.
     *
     * @return void
     */
    public function testGetUsers()
    {
        // Test Passport authenticated APIs
        Passport::actingAs(
            factory(User::class)->create(),
        );
        $response = $this->get('/api/users');
        $response->assertStatus(200);
    }
}
