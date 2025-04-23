<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
class ProviderController
{
    public function index()
    {
        $providers = DB::table('providers')
            ->where('status', 1)->get();
        return view('providers', ['providers' => $providers]);
    }

    public function store(Request $request)
    {
        // Verificar si el proveedor ya existe
        $provider = Provider::where('provider_nit', $request->input('ProviderIdentification'))->first();

        if (is_null($provider)) {  // Si no existe, crear uno nuevo
            $provider = new Provider();
            $provider->provider_name = $request->input('providerName');
            $provider->provider_number = $request->input('providerPhone');
            $provider->provider_email = $request->input('providerEmail');
            $provider->provider_nit = $request->input('ProviderIdentification');

            try {
                $provider->save();
                return redirect()->route('providers')->with('status', 'Proveedor agregado correctamente.');
            } catch (\Exception $e) {
                return back()->with('error', 'Error al agregar el proveedor: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('providers')->with('error', 'El proveedor ya existe.');
        }
    }
    public function update(Request $request)
    {
        try {
            // Buscar el proveedor por NIT
            $provider = Provider::where('provider_nit', $request->input('ProviderIdentification'))->first();

            // Verificar si el proveedor existe
            if (!$provider) {
                return back()->with('error', 'Proveedor no encontrado.');
            }

            // Actualizar los datos
            $provider->provider_name = $request->input('providerName');
            $provider->provider_number = $request->input('providerPhone');
            $provider->provider_email = $request->input('providerEmail');
            $provider->provider_nit = $request->input('ProviderIdentification');

            // Guardar cambios
            $provider->save();

            return redirect()->route('providers');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $provider = Provider::find($id);

        if (!$provider) {
            return redirect()->route('providers')->with('status', 'Proveedor no encontrado.');
        }

        try {
            // Inhabilitar proveedor
            $provider->update(['status' => 0]);

            // Inhabilitar productos relacionados
            $updatedProducts = $provider->products()->each(function ($product) {
                $product->update(['prod_status' => 0]);
            });

            // Verificar si los productos se actualizaron


            return redirect()->route('providers')->with('status', 'Proveedor inhabilitado. Productos también han sido inhabilitados.');
        } catch (\Exception $e) {
            return redirect()->route('providers')->with('error', 'Error al inhabilitar el proveedor: ' . $e->getMessage());
        }
    }



}
