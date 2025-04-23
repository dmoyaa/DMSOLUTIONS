<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/products.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar ></x-lateral-bar>

    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">

            <h1>PRODUCTOS</h1>
            <div class="search-bar">
                {{--                <button class="new-button" id="openModalButton"><i class="fas fa-plus"></i> Nuevo</button>--}}
                <button class="new-button" id="download" action=""></i> <a href="{{ route('descargarPlantilla') }}" >Descargar Plantilla</a></button>
                <button class="new-button" id="openModalUpdadteButton">
                    <i>Actualizar Productos</i>
                </button>
            </div>
        </header>
        <div class="table-container">
            <table id="products-table" class="project-table">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Id</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($products)
                    @foreach($products as $product)
                        <tr>
                            <td>{{$product->prod_name}}</td>
                            <td>{{$product->prod_des}}</td>
                            <td>{{$product->id}}</td>
                            @if($product->prod_status == 1)
                                <td>Activo</td>
                            @else
                                <td>Inactivo</td>
                            @endif
                            <td>{{ number_format($product->precio_convertido, 2) }} COP</td>

                            <td>
{{--                                {{ $product->money_exchange }} {{ number_format($product->prod_price_sales, 2) }}--}}
{{--                                →--}}
{{--                                {{ number_format($product->precio_convertido, 2) }} COP--}}
                                <img style="max-width: 85px;" src="{{asset($product -> prod_image)}}" alt="">
                            </td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        @php
                                            $user = session('user_id') ? DB::table('users')->where('id', session('user_id'))->first() : null;
                                        @endphp

                                        @if ($user && $user->user_role == '1')
                                            <form action="{{ route('product-delete',['id' => $product->id])}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="action-btn">Eliminar</button>
                                            </form>
                                        @endif
                                        <button class="action-btn view-update-provider" id="updateSingleModal" >Actualizar</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>
        </div>
    </main>
</div>

<div class="modal hidden" id="uploadPoductsModal">
    <div class="modal-content">
        <label>Por favor  anexe el formato actualizado con los productos que desea actualizar</label>
        <form action="{{ route('prod-upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="archivo" class="form-control mb-2" required>
            <button type="submit" class="btn btn-success">Subir Archivo</button>
            <button type="button" id="closeUploadProductsModal" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>

<!-- Modal para agregar producto -->
<div class="modal hidden" id="productModal">
    <div class="modal-content">
        <h2>Actualizar Producto</h2>
        <form id="productForm" method="post" action="{{ route('product-single-upload') }}">
            @csrf
            @method('PATCH')

            <label for="productName">Nombre:</label>
            <input type="text" id="productName" name="productName" value="{{ old('productName') }}" required>

            <input type="hidden" name="hiddenProjectId" id="hiddenProjectId">

            <label for="productDescription">Descripción:</label>
            <textarea id="productDescription" name="productDescription" required>{{ old('productDescription') }}</textarea>

            <label for="productPrice">Precio:</label>
            <input type="number" id="productPrice" name="productPrice" required>

            <button type="submit">Guardar Producto</button>
            <button type="button" id="closeFormButton1" class="close-form-button">Cerrar</button>
        </form>
    </div>
</div>

<script>
    const openModaUpdatelButton = document.getElementById('openModalUpdadteButton');
    const modal = document.getElementById("productModal");
    document.addEventListener("DOMContentLoaded", () => {

        const uploadmodal = document.getElementById("uploadPoductsModal");
        const closeuploadmodal = document.getElementById("closeUploadProductsModal");
        const openModalButton = document.getElementById("updateSingleModal");
        const closeFormButton = document.getElementById("closeFormButton1");
        const openModaUpdatelButton = document.getElementById('openModalUpdadteButton');

        openModaUpdatelButton.addEventListener("click", () => {
            uploadmodal.classList.remove("hidden");
        });
        closeuploadmodal.addEventListener("click", () => {
            uploadmodal.classList.add("hidden");
        });
        // Abre el modal al hacer clic en el botón "New"
        openModalButton.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        // Cierra el modal al hacer clic en "Cerrar"
        closeFormButton.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Cierra el modal si el usuario hace clic fuera del contenido del modal
        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });

    const actionMenus = document.querySelectorAll('.action-menu');

    actionMenus.forEach(menu => {
        const dots = menu.querySelector('.action-dots');
        const dropdown = menu.querySelector('.action-dropdown');

        dots.addEventListener('click', () => {
            document.querySelectorAll('.action-dropdown').forEach(drop => {
                if (drop !== dropdown) {
                    drop.classList.add('hidden');
                }
            });

            dropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    // document.getElementById('productForm').addEventListener('submit', (event) => {
    //     event.preventDefault();
    //
    //     const name = document.getElementById('productName').value;
    //     const description = document.getElementById('productDescription').value;
    //     const price = document.getElementById('productPrice').value;
    //
    //     if (name && description && price) {
    //         alert('Producto guardado con éxito');
    //         document.getElementById('productModal').classList.add('hidden');
    //         document.getElementById('productForm').reset();
    //     } else {
    //         alert('Por favor completa todos los campos.');
    //     }
    // });
    const botonesDetalle = document.querySelectorAll('.view-update-provider');
    botonesDetalle.forEach(function(boton) {
        boton.addEventListener('click', function(event) {
            // Evitar que el clic en el botón se propague
            event.stopPropagation();

            // Obtener la fila correspondiente al botón clickeado
            let fila = boton.closest('tr');

            // Acceder a los datos de la fila (ID, Fecha de expiración, Nombre, Teléfono, etc.)
            document.getElementById("productName").setAttribute("value",fila.cells[0].textContent);

            document.getElementById("productDescription").textContent=fila.cells[1].textContent;
            document.getElementById("hiddenProjectId").value = fila.cells[2].textContent.replace(/,/g, '');
            document.getElementById('productPrice').value = fila.cells[4].textContent.replace(/,/g, '');
            modal.classList.remove("hidden");
        });
    });

    $(document).ready(function() {
        $('#products-table').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            },
            pageLength: 10,
            lengthChange: false,
            order: [[ 0, "asc" ]],
            columnDefs: [
                {
                    targets: -1, // Última columna ("Acciones")
                    orderable: false, // Desactiva ordenamiento
                    searchable: false // Desactiva búsqueda
                }
            ]// Ordena por ID descendente por defecto
        });
    });
</script>
</body>
</html>
