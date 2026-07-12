<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Notifications\NewContactNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_submit_a_contact_message(): void
    {
        Notification::fake();

        $this->post(route('contact.store'), [
            'name' => 'Visitor',
            'email' => 'visitor@example.com',
            'message' => 'Hello, I would like a quote.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contacts', ['email' => 'visitor@example.com']);
        Notification::assertCount(1);
    }

    public function test_honeypot_blocks_spam(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'message' => 'spam spam spam',
            'website' => 'http://spam.example',
        ])->assertRedirect();

        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_contact_requires_valid_email(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Visitor',
            'email' => 'not-an-email',
            'message' => 'Hi',
        ])->assertSessionHasErrors('email');
    }
}
