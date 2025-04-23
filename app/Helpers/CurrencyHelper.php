<?php
// app/Helpers/CurrencyHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyHelper
{
    public static function obtenerTasaCambio($monedaOrigen, $monedaDestino = 'COP')
    {
        if ($monedaOrigen === $monedaDestino) {
            return 1;
        }

        $cacheKey = "exchange_rate_{$monedaOrigen}_to_{$monedaDestino}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($monedaOrigen, $monedaDestino) {
            try {
                $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$monedaOrigen}");
                if ($response->successful()) {
                    return $response['rates'][$monedaDestino] ?? 1;
                }
            } catch (\Exception $e) {
                \Log::error("Error obteniendo tasa de cambio: " . $e->getMessage());
            }

            return 1; // Valor por defecto si falla la API
        });
    }
}
