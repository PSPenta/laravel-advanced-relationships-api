<?php

namespace Tests\Unit\Http\Controllers\Api;

use App\Models\User;
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
        $response = $this->get('/api/users', ['HTTP_Authorization' => 'Bearer '.env("AUTH0_TEST_TOKEN")]);
        $response->assertStatus(200);
    }
}
