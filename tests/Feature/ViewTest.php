<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Quote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTest extends TestCase
{
    use RefreshDatabase;

    // Limpia la base de datos después de cada prueba

    /** @test */
    public function projectView()
    {
        $response = $this->get('/browse'); // Ruta de la vista
        $response->assertStatus(200);
    }
    /** @test */
    public function providersView()
    {
        $response = $this->get('/providers'); // Ruta de la vista
        $response->assertStatus(200);
    }
    /** @test */
    public function quoteView()
    {
        $response = $this->get('/quote'); // Ruta de la vista

        $response->assertStatus(200);
    }
    /** @test */
    public function clientsView()
    {
        $response = $this->get('/clients'); // Ruta de la vista

        $response->assertStatus(200);
    }
    /** @test */
    public function productsView()
    {
        $response = $this->get('/products'); // Ruta de la vista
        $response->assertStatus(200);
    }
    /** @test */
    public function administrationView()
    {
        $response = $this->get('/administration'); // Ruta de la vista

        $response->assertStatus(200);
    }
    /** @test */
    public function remindersView()
    {
        $response = $this->get('/reminders'); // Ruta de la vista
        $response->assertStatus(200);
    }

}
