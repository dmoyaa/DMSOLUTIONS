<?php

namespace App\Http\Controllers;

use App\Exports\PlantillaProductosExport;
use App\Imports\ProductsImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Helpers\CurrencyHelper;
class ProductController
{



    public function index()
    {
        $products = DB::table('products')
            ->where('prod_status', 1)
            ->get();

        foreach ($products as $product) {
            $tasa = CurrencyHelper::obtenerTasaCambio($product->money_exchange, 'COP');
            $precioOriginal = $product->prod_price_purchase;
            $precioConvertido = $precioOriginal * $tasa;

            $product->precio_convertido = $precioConvertido;
        }

        return view('products', compact('products'));
    }

    public function show()
    {
        $product = DB::table('products')->get();
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products')->with('status', 'Producto no encontrado.');
        }
        try {
            // Inhabilitar proveedor
            $product->update(['prod_status' => 0]);
            // Verificar si los productos se actualizaron
            return redirect()->route('products')
                ->with('status', 'Producto inhabilitado. Productos también han sido inhabilitados.');
        } catch (\Exception $e) {
            return redirect()->route('products')
                ->with('error', 'Error al inhabilitar el producto: ' . $e->getMessage());
        }
    }
    public function consult()
    {
        try {
            $products = DB::table('products')->select('prod_name', 'prod_price_sales', 'provider_id')->get();
            if ($products->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron productos'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los productos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function descargarPlantilla()
    {
        return Excel::download(new PlantillaProductosExport, 'plantilla_productos.xlsx');
    }


    public function upload(Request $request)
    {
        // Validar que el archivo es un Excel
        $request->validate([
            'archivo' => 'required|mimes:xlsx,csv',
        ]);

        try {
            // Obtener el archivo
            $file = $request->file('archivo');

            // Ruta de almacenamiento segura
            $destinationPath = storage_path('app/temp');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Guardar el archivo
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);

            // Ruta final del archivo
            $fullPath = "{$destinationPath}/{$fileName}";

            // Verificar si el archivo realmente existe
            if (!file_exists($fullPath)) {
                throw new \Exception("Error: el archivo no se guardó en {$fullPath}");
            }

            // Procesar la importación del archivo
            $import = new ProductsImport($fullPath);
            Excel::import($import, $fullPath);

            // Eliminar el archivo después de la importación
            unlink($fullPath);

            // Obtener productos no insertados
            $productosNoInsertados = $import->getErrores();


            $message = 'Importación completada. Productos no insertados: ' . implode(', ', $productosNoInsertados);

        } catch (\Exception $e) {
            $message = $e->getCode();
        }
        return to_route('products')->with('status', $message);
    }
    public function singleUpload(Request $request)
    {
        // Verificar si los datos se están enviando correctamente


        // Buscar el producto por el ID proporcionado
        $product = Product::find($request->get('hiddenProjectId'));

        // Verificar si el producto existe
        if (!$product) {
            return to_route('products')->with('status', 'Error: Producto no encontrado.');
        }

        try {

            // Actualizar los datos del producto
            $product->update([
                'prod_name' => $request->get('productName'),
                'prod_des' => $request->get('productDescription'),
//                'prod_price_purchase' => $request->get('productPrice'),
                'prod_price_sales'=> $request->get('productPrice'),
                'prod_status' => 1,
            ]);

            $message = 'Producto actualizado correctamente.';
        } catch (\Exception $e) {
            $message = 'Error al actualizar el producto: ' . $e->getMessage();
        }

        return to_route('products')->with('status', $message);
    }



}
