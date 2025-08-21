<?php

namespace Tests\Feature\Api\Demanda;

use App\Models\Demanda\Demanda;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemandaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function test_user_can_list_demandas()
    {
        $this->authenticate();

        Demanda::factory()->count(3)->create();

        $response = $this->getJson('/api/demandas');

        $response->assertOk()
                 ->assertJsonCount(3);
    }

    public function test_user_can_create_demanda()
    {
        $this->authenticate();

        $payload = [
            'produto' => 'Sistema XYZ',
            'chamado' => 'CH12345',
            'descricao' => 'Corrigir bug na tela de login',
            'tipo' => 'bug',
            'data_previsao' => now()->addDays(5)->toDateString(),
            'cliente' => 'Cliente A',
            'responsavel_id' => null,
            'status' => 'em_branco',
            'prioridade' => 'verde',
        ];

        $response = $this->postJson('/api/demandas', $payload);

        $response->assertCreated()
                 ->assertJsonFragment([
                     'produto' => 'Sistema XYZ',
                     'tipo' => 'bug',
                 ]);
    }
}
