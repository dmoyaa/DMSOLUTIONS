<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/clients.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<body>
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar></x-lateral-bar>
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>CLIENTES</h1>
            <div class="search-bar">
                <button class="new-button" id="openModalButton"><i class="fas fa-plus"></i> Nuevo</button>
            </div>
        </header>
        <div class="table-container">
            <table id="project-table" class="project-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Identificación</th>
                    <th>Dirección</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                @isset($clients)
                    @foreach($clients as $client)
                        <tr>
                            <td>{{$client->id}}</td>
                            <td>{{$client->client_name}}</td>
                            <td>{{$client->client_ph}}</td>
                            <td>{{$client->client_email}}</td>
                            <td>{{$client->client_identification}}</td>
                            <td>{{$client->client_address}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="action-btn view-update-client" id="open-update-btn">Actualizar</button>
                                        @php
                                            $user = session('user_id') ? DB::table('users')->where('id', session('user_id'))->first() : null;
                                        @endphp
                                        @if ($user && $user->user_role == '1')
                                            <form action="{{route('client-delete',$client)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="action-btn">Eliminar</button>
                                            </form>
                                        @endif
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

<!-- Modal para agregar cliente -->
<div class="modal hidden" id="clientModal">
    <div class="modal-content">
        <h2>Actualizar Cliente</h2>
        <br>
        <form id="clientForm" method="POST" action="{{route('client-save')}}">
            @csrf
            <label for="clientName">Nombre:</label>
            <input type="text" name="clientName" id="clientName" value="{{old('clientName')}}" required>

            <label for="clientPhone">Teléfono:</label>
            <input type="text" name="clientPhone" id="clientPhone" value="{{old('clientPhone')}}" required>

            <label for="clientEmail">Email:</label>
            <input type="email" name="clientEmail" id="clientEmail" value="{{old('clientEmail')}}" required>

            <label for="clientIdentification">Identificación:</label>
            <input type="text" name="clientIdentification" id="clientIdentification" value="{{old('clientIdentification')}}" required>

            <label for="clientAddress">Dirección:</label>
            <input type="text" name="clientAddress" id="clientAddress" value="{{old('clientAddress')}}" required>

            <button type="submit" class="modal-button">Guardar Cliente</button>

            <button type="button" id="closeFormButton" class="modal-button">Cerrar</button>

        </form>
    </div>
</div>

<!-- Modal para actualizar cliente -->
<div class="modal hidden" id="clientUpdate">
    <div class="modal-content">
        <h2>Agregar Cliente</h2>
        <br>
        <form id="clientForm" method="POST" action="{{ route('client-update') }}">
            @csrf @method('PATCH')
            <label for="clientName">Nombre:</label>
            <input type="text" name="clientName" id="update-clientName" value="{{old('clientName')}}" required>

            <label for="clientPhone">Teléfono:</label>
            <input type="text" name="clientPhone" id="update-clientPhone" value="{{old('clientPhone')}}" required>

            <label for="clientEmail">Email:</label>
            <input type="email" name="clientEmail" id="update-clientEmail" value="{{old('clientEmail')}}" required>

            <label for="clientIdentification">Identificación:</label>
            <input type="text" name="clientIdentification" id="update-clientIdentification" value="{{old('clientIdentification')}}" required>

            <label for="clientAddress">Dirección:</label>
            <input type="text" name="clientAddress" id="update-clientAddress" value="{{old('clientAddress')}}" required>

            <button type="submit" id="updateButton" class="modal-button">Actualizar Cliente</button>

            <button type="button" id="closeUpdateButton" class="modal-button">Cerrar</button>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("clientModal");
        const openModalButton = document.getElementById("openModalButton");
        const closeFormButton = document.getElementById("closeFormButton");

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

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById("clientUpdate");
        const openModalButton = document.getElementById("open-update-btn");
        const closeFormButton = document.getElementById("closeUpdateButton");
        const botonesDetalle = document.querySelectorAll('.view-update-client');

        botonesDetalle.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();

                // Obtener la fila correspondiente al botón clickeado
                let fila = boton.closest('tr');

                // Acceder a los datos de la fila (ID, Fecha de expiración, Nombre, Teléfono, etc.)
                document.getElementById("update-clientName").setAttribute("value",fila.cells[1].textContent);
                document.getElementById("update-clientPhone").setAttribute("value",fila.cells[2].textContent);
                document.getElementById("update-clientEmail").setAttribute("value",fila.cells[3].textContent);
                document.getElementById("update-clientIdentification").setAttribute("value",fila.cells[4].textContent);
                document.getElementById("update-clientAddress").setAttribute("value",fila.cells[5].textContent);

                modal.classList.remove("hidden");
            });
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

    $(document).ready(function() {
        $('#project-table').DataTable({
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
