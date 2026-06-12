<?php

namespace Tests\Feature;

use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiveStreamAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['stream.user_access_api_key' => 'viewer-secret']);
        config(['stream.castr_player_url' => 'https://player.castr.com/live_0c20d4f0666e11f1a569db35c9fe0782']);
    }

    public function test_visitor_details_are_recorded_before_showing_player(): void
    {
        $response = $this->post('/watch', [
            'api_key' => 'viewer-secret',
            'name' => 'Test Viewer',
            'mobile' => '+91 9876543210',
            'email' => 'viewer@example.com',
        ]);

        $visitor = Visitor::first();

        $response
            ->assertRedirect('/live')
            ->assertSessionHas('visitor_id', $visitor->id);

        $this->assertSame('Test Viewer', $visitor->name);
        $this->assertSame('+91 9876543210', $visitor->mobile);
        $this->assertSame(hash('sha256', 'viewer-secret'), $visitor->api_key_hash);
        $this->assertSame('cret', $visitor->api_key_suffix);

        $this->get('/live')
            ->assertOk()
            ->assertSee('FIFA Live Stream')
            ->assertSee('Test Viewer')
            ->assertSee('https://player.castr.com/live_0c20d4f0666e11f1a569db35c9fe0782')
            ->assertSee('js/tv-remote.js');
    }

    public function test_invalid_user_api_key_does_not_create_visitor(): void
    {
        $this->post('/watch', [
            'api_key' => 'wrong-key',
            'name' => 'Rejected Viewer',
            'mobile' => '+91 9876543210',
            'email' => 'viewer@example.com',
        ])
            ->assertSessionHasErrors('api_key')
            ->assertSessionMissing('visitor_id');

        $this->assertDatabaseMissing('visitors', [
            'name' => 'Rejected Viewer',
        ]);
    }

    public function test_missing_user_api_key_does_not_create_visitor(): void
    {
        config(['stream.user_access_api_key' => '']);

        $this->post('/watch', [
            'api_key' => 'viewer-secret',
            'name' => 'Rejected Viewer',
            'mobile' => '+91 9876543210',
        ])
            ->assertSessionHasErrors('api_key')
            ->assertSessionMissing('visitor_id');

        $this->assertDatabaseCount('visitors', 0);
    }

    public function test_player_redirects_to_form_without_visitor_session(): void
    {
        $this->get('/live')
            ->assertRedirect('/');
    }

    public function test_admin_can_block_and_unblock_a_visitor_mobile(): void
    {
        $visitor = Visitor::create([
            'name' => 'Blocked Viewer',
            'mobile' => '9876543210',
            'last_seen_at' => now(),
        ]);

        $this->withSession(['admin_access_granted' => true])->post(route('admin.visitors.block', $visitor), [
            'reason' => 'Test block',
        ])->assertRedirect(route('admin.visitors'));

        $visitor->refresh();
        $this->assertTrue($visitor->is_blocked);
        $this->assertSame('Test block', $visitor->block_reason);

        $this->withSession(['admin_access_granted' => true])
            ->post(route('admin.visitors.unblock', $visitor))
            ->assertRedirect(route('admin.visitors'));

        $visitor->refresh();
        $this->assertFalse($visitor->is_blocked);
        $this->assertNull($visitor->block_reason);
    }

    public function test_admin_logs_in_with_api_token_box(): void
    {
        config(['stream.admin_token' => 'secret']);

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('API access token')
            ->assertSee('js/tv-remote.js');

        $this->get('/admin/visitors')
            ->assertRedirect('/admin/login');

        $this->post('/admin/login', [
            'api_token' => 'wrong',
        ])
            ->assertSessionHasErrors('api_token')
            ->assertSessionMissing('admin_access_granted');

        $this->post('/admin/login', [
            'api_token' => 'secret',
        ])
            ->assertRedirect('/admin/visitors')
            ->assertSessionHas('admin_access_granted', true);

        $this->withSession(['admin_access_granted' => true])
            ->get('/admin/visitors')
            ->assertOk()
            ->assertSee('Live Stream Visitors');
    }

    public function test_blocked_mobile_cannot_bypass_by_registering_again(): void
    {
        Visitor::create([
            'name' => 'Blocked Viewer',
            'mobile' => '9876543210',
            'is_blocked' => true,
            'blocked_at' => now(),
            'block_reason' => 'No access',
        ]);

        $this->post('/watch', [
            'api_key' => 'viewer-secret',
            'name' => 'New Attempt',
            'mobile' => '9876543210',
        ])->assertRedirect('/live');

        $newAttempt = Visitor::where('name', 'New Attempt')->firstOrFail();

        $this->assertTrue($newAttempt->is_blocked);
        $this->assertSame('No access', $newAttempt->block_reason);

        $this->get('/live')
            ->assertOk()
            ->assertSee('Access Blocked')
            ->assertSee('No access')
            ->assertSee('js/tv-remote.js');
    }
}
