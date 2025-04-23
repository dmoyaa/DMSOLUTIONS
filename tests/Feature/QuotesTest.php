<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuotesTest extends TestCase
{
    use RefreshDatabase; // Limpia la base de datos después de cada prueba

    /** @test */
    public function la_vista_de_cotizaciones_se_renderiza_correctamente()
    {
        $response = $this->get(route('quote')); // Ruta de la vista

        $response->assertStatus(200);
        $response->assertSee('Lista de Cotizaciones'); // Ajusta según el contenido de tu vista
    }
}
