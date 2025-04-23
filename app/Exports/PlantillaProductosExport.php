<?php


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PlantillaProductosExport implements FromArray
{
    public function array(): array
    {
        return [
            ['Nombre del Producto', 'referencia','Descripcion','Proveedor','Precio de compra', 'Moneda', 'Imagen'],
            ['Switch Adminstrable','DH-PFS4212-8GT-96-V2','SWITCH POE ADMINIST. CAPA2 12 PUERTOS GIGA 8 POE 2 PUERTOS HASTA 60W RESTANTES A 30W TOTAL MAX 90W','GOBAL VIDEO SYSTEMS','100', 'USD', 'camara.jpg'],
        ];
    }
}
