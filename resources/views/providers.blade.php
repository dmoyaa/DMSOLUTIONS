<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Providers - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/providers.css')}}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<div class="container">
    <!-- Barra lateral -->
    <x-lateral-bar></x-lateral-bar>

    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>PROVEEDORES</h1>
            <div class="search-bar">
                <button class="new-button" id="openModalButton"><i class="fas fa-plus"></i> Nuevo</button>
            </div>
        </header>
        <div class="table-container">
            <table id="project-table" class="project-table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>NIT</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @isset($providers)
                    @foreach($providers as $provider)
                        <tr>
                            <td>{{$provider->id}}</td>
                            <td>{{$provider->provider_nit}}</td>
                            <td>{{$provider->provider_name}}</td>
                            <td>{{$provider->provider_number}}</td>
                            <td>{{$provider->provider_email}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <form action="{{ route('provider-delete', ['id' => $provider->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn">Eliminar</button>
                                        </form>
                                        <button class="action-btn view-update-provider" id="openModalButtonUpdate">Actualizar</button>
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

<!-- Modal para agregar proveedor -->
<div class="modal hidden" id="providerModal">
    <div class="modal-content">
        <h2>Add Provider</h2>

        <form id="porviderForm" method="POST" action="{{ route('provider-save') }}">
            @csrf
            <label for="providerName">Nombre:</label>
            <input type="text" name="providerName" id="updateName" required>

            <label for="providerPhone">Teléfono:</label>
            <input type="text" name="providerPhone" id="updatePhone" required>

            <label for="providerEmail">Email:</label>
            <input type="email" name="providerEmail" id="updateEmail"  required>

            <label for="ProviderIdentification">Identificación:</label>
            <input type="text" name="ProviderIdentification" id="updateIdentification"  required>


            <button type="submit" id="updateButton">Guardar Proveedor</button>

            <button type="button" id="closeFormButton" class="close-form-button">Cerrar</button>

        </form>
    </div>
</div>

<!-- Modal para actualizar proveedro -->
<div class="modal hidden" id="provaiderUpdate">
    <div class="modal-content">
        <h2>Actualizar Proveedor</h2>
        <br>
        <form id="porviderForm" method="POST" action="{{ route('provider-update') }}">
            @csrf @method('PATCH')
            <label for="providerName">Nombre:</label>
            <input type="text" name="providerName" id="updateNameForm" value="{{old('providerName')}}" required>

            <label for="providerPhone">Teléfono:</label>
            <input type="text" name="providerPhone" id="updatePhoneForm" value="{{old('providerPhone')}}" required>

            <label for="providerEmail">Email:</label>
            <input type="email" name="providerEmail" id="updateEmailForm" value="{{old('providerEmail')}}" required>

            <label for="ProviderIdentification">Identificación:</label>
            <input type="text" name="ProviderIdentification" id="updateIdentificationForm" value="{{old('ProviderIdentification')}}" required>


            <button type="submit" id="updateButton">Actualizar Proveedor</button>

            <button type="button" id="closeUpdateButton" class="close-form-button">Cerrar</button>

        </form>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("providerModal");
        const openModalButton = document.getElementById("openModalButton");
        const closeFormButton = document.getElementById("closeFormButton");

        //Abre el modal al hacer clic en el botón "New"
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




    const updateModal = document.getElementById("provaiderUpdate");
    const botonesDetalle = document.querySelectorAll('.view-update-provider');
    botonesDetalle.forEach(function(boton) {
        boton.addEventListener('click', function(event) {

            // Evitar que el clic en el botón se propague
            event.stopPropagation();

            // Obtener la fila correspondiente al botón clickeado
            let fila = boton.closest('tr');

            // Acceder a los datos de la fila (ID, Fecha de expiración, Nombre, Teléfono, etc.)
            document.getElementById("updateNameForm").setAttribute("value",fila.cells[2].textContent);
            document.getElementById("updatePhoneForm").setAttribute("value",fila.cells[3].textContent);
            document.getElementById("updateEmailForm").setAttribute("value",fila.cells[4].textContent);
            document.getElementById("updateIdentificationForm").setAttribute("value",fila.cells[1].textContent);
            //document.getElementById("update-clientAddress").setAttribute("value",fila.cells[5].textContent);

            updateModal.classList.remove("hidden");


        });
    });
    const deleteModalButton = document.getElementById("closeUpdateButton");
    deleteModalButton.addEventListener("click", () => {
        updateModal.classList.add("hidden");
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
