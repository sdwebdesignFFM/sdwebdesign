<?php

namespace Tests\Feature;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Models\Client;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_client_model_can_be_created(): void
    {
        $client = Client::factory()->create([
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'company' => 'Test GmbH',
            'email' => 'max@test.de',
        ]);

        $this->assertDatabaseHas('clients', [
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'company' => 'Test GmbH',
            'email' => 'max@test.de',
        ]);
    }

    public function test_client_full_name(): void
    {
        $client = Client::factory()->create([
            'title' => 'Dr.',
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
        ]);

        $this->assertEquals('Dr. Max Mustermann', $client->full_name);
    }

    public function test_client_full_name_without_title(): void
    {
        $client = Client::factory()->create([
            'title' => null,
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
        ]);

        $this->assertEquals('Max Mustermann', $client->full_name);
    }

    public function test_client_display_name_with_company(): void
    {
        $client = Client::factory()->create([
            'title' => null,
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'company' => 'Test GmbH',
        ]);

        $this->assertEquals('Test GmbH (Max Mustermann)', $client->display_name);
    }

    public function test_client_display_name_without_company(): void
    {
        $client = Client::factory()->create([
            'title' => null,
            'first_name' => 'Max',
            'last_name' => 'Mustermann',
            'company' => null,
        ]);

        $this->assertEquals('Max Mustermann', $client->display_name);
    }

    public function test_client_full_address(): void
    {
        $client = Client::factory()->create([
            'street' => 'Teststraße 1',
            'zip' => '12345',
            'city' => 'Berlin',
            'country' => 'Deutschland',
        ]);

        $this->assertEquals("Teststraße 1\n12345 Berlin", $client->full_address);
    }

    public function test_client_full_address_with_foreign_country(): void
    {
        $client = Client::factory()->create([
            'street' => 'Main Street 1',
            'zip' => '10001',
            'city' => 'New York',
            'country' => 'USA',
        ]);

        $this->assertEquals("Main Street 1\n10001 New York\nUSA", $client->full_address);
    }

    public function test_client_can_have_quotes(): void
    {
        $client = Client::factory()->create();
        $quote = Quote::factory()->create(['client_id' => $client->id]);

        $this->assertTrue($client->quotes->contains($quote));
        $this->assertEquals($client->id, $quote->client_id);
    }

    public function test_quote_belongs_to_client(): void
    {
        $client = Client::factory()->create();
        $quote = Quote::factory()->create(['client_id' => $client->id]);

        $this->assertEquals($client->id, $quote->client->id);
    }

    public function test_client_list_page_loads(): void
    {
        Livewire::test(ListClients::class)
            ->assertOk();
    }

    public function test_client_create_page_loads(): void
    {
        Livewire::test(CreateClient::class)
            ->assertOk();
    }

    public function test_client_can_be_created_via_filament(): void
    {
        Livewire::test(CreateClient::class)
            ->fillForm([
                'salutation' => 'Herr',
                'first_name' => 'Neuer',
                'last_name' => 'Kunde',
                'company' => 'Neue Firma GmbH',
                'email' => 'neu@test.de',
                'phone' => '0123456789',
                'street' => 'Teststraße 1',
                'zip' => '12345',
                'city' => 'Berlin',
                'country' => 'Deutschland',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('clients', [
            'salutation' => 'Herr',
            'first_name' => 'Neuer',
            'last_name' => 'Kunde',
            'company' => 'Neue Firma GmbH',
            'email' => 'neu@test.de',
        ]);
    }

    public function test_client_edit_page_loads(): void
    {
        $client = Client::factory()->create();

        Livewire::test(EditClient::class, ['record' => $client->id])
            ->assertOk();
    }

    public function test_client_can_be_updated_via_filament(): void
    {
        $client = Client::factory()->create([
            'last_name' => 'Original',
        ]);

        Livewire::test(EditClient::class, ['record' => $client->id])
            ->fillForm([
                'last_name' => 'Updated',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'last_name' => 'Updated',
        ]);
    }

    public function test_client_requires_last_name(): void
    {
        Livewire::test(CreateClient::class)
            ->fillForm([
                'last_name' => '',
                'email' => 'test@test.de',
            ])
            ->call('create')
            ->assertHasFormErrors(['last_name' => 'required']);
    }

    public function test_client_requires_email(): void
    {
        Livewire::test(CreateClient::class)
            ->fillForm([
                'last_name' => 'Test',
                'email' => '',
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'required']);
    }

    public function test_client_email_must_be_valid(): void
    {
        Livewire::test(CreateClient::class)
            ->fillForm([
                'last_name' => 'Test',
                'email' => 'invalid-email',
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'email']);
    }
}
