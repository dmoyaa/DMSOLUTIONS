<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function DashboardStatus()
    {
        $response = $this->get('/dashboard/status'); // Ruta de la vista
        $response->assertStatus(200);
    }
    /** @test */
    public function DashboardClients()
    {
        $response = $this->get('/dashboard/clients'); // Ruta de la vista
        $response->assertStatus(200);
    }
    /** @test */
    public function DashboardMonth()
    {
        $response = $this->get('/dashboard/month'); // Ruta de la vista
        $response->assertStatus(200);
    }
    /** @test */
    public function DashboardQuotes()
    {
        $response = $this->get('/dashboard/quotes'); // Ruta de la vista
        $response->assertStatus(200);
    }

}
