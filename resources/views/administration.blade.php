<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - DM Solutions</title>
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
            <h1>ADMINISTRACIÓN</h1>
            <div class="search-bar">
                <button class="new-button" id="openModalButton">
                    <i class="fas fa-plus"></i> Nuevo
                </button>
            </div>
        </header>
        <div class="table-container">
            <table id="project-table" class="project-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                @isset($users)
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->user_first_name}}</td>
                            <td>{{$user->user_last_name}}</td>
                            <td>{{$user->user_email}}</td>
                            <td>{{$user->role_name}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="action-btn edit-project">Actualizar</button>
                                        <form action="{{route('user-delete',$user -> id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endisset
                <!-- Más filas -->
                </tbody>
            </table>
        </div>
    </main>
</div>
!-- Modal para agregar user -->
<div class="modal hidden" id="userModal">
    <div class="modal-content">
        <h2>Agregar Usuario</h2>
        <br>
        <form id="userForm" method="POST" action="{{route('user-save')}}">
            @csrf
            <label for="userName">Nombres:</label>
            <input type="text" name="userNames" id="userNames" value="{{old('userNames')}}" required>

            <label for="userlastName">Apellidos:</label>
            <input type="text" name="userLastNames" id="userLastNames" value="{{old('userLastNames')}}" required>

            <label for="userEmail">Email:</label>
            <input type="email" name="userEmail" id="userEmail" value="{{old('userEmail')}}" required>

            <label for="userPassword">Contraseña:</label>
            <input type="password" name="userPassword" id="userPassword" value="{{old('userPassword')}}" required>

            <label for="menu">Rol:</label>
            <select id="menuRol" name="menuRol">

            </select>
            <button type="submit" class="modal-button">Guardar usuario</button>

            <button type="button" id="closeFormButton" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>
!-- Modal para actualizar usurio -->
<div class="modal hidden" id="updateModal">
    <div class="modal-content">
        <h2>Actualizar Usuario</h2>
        <br>
        <form id="userUpdateForm" method="POST" action="{{route('user-update')}}">
            @csrf @method('PATCH')
            <input class="hidden" type="text" name="hiddenUserId" id="hiddenUserId">
            <label for="userName">Nombres:</label>
            <input type="text" name="updateUserNames" id="updateUserNames" value="{{old('userNames')}}" required>

            <label for="userlastName">Apellidos:</label>
            <input type="text" name="updateUserLastNames" id="updateUserLastNames" value="{{old('userLastNames')}}" required>

            <label for="userEmail">Email:</label>
            <input type="email" name="updateUserEmail" id="updateUserEmail" value="{{old('userEmail')}}" required>

            <label for="userPassword">Contraseña:</label>
            <input type="password" name="updateUserPassword" id="updateUserPassword" value="{{old('userPassword')}}" >

            <label for="menu">Rol:</label>
            <select id="menuActualizarRol" name="menuActualizarRol">

            </select>
            <button type="submit" class="modal-button">Guardar usuario</button>

            <button type="button" id="closeUpdateFormButton" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>
<script>
    const rolOptions = document.getElementById('menuRol');
    const openNewUser = document.getElementById('openModalButton')
    const closeNewUser = document.getElementById('closeFormButton')
    const openUserModal = document.getElementById('userModal')
    const openUpdateModal = document.querySelectorAll('.edit-project')
    const updateRol = document.getElementById('menuActualizarRol')
    const updateModal = document.getElementById('updateModal')
    const closeUpdate = document.getElementById('closeUpdateFormButton')

    //Añadir Datos en modal actualizar
    document.addEventListener('DOMContentLoaded', function() {
        openUpdateModal.forEach(function(boton) {
            let role_id = 0;
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();
                // Obtener la fila correspondiente al botón clickeado

                let fila = boton.closest('tr');
                let userDetail = fila.cells[0].textContent;
                console.log(userDetail);
                fetch(`user/detail/${userDetail}`)
                    .then(response => response.json())
                    .then(data => {
                        role_id = data.user_role;
                        document.getElementById("hiddenUserId").value = data.id;
                        document.getElementById("updateUserNames").value = data.user_first_name;
                        document.getElementById("updateUserLastNames").value = data.user_last_name;
                        document.getElementById("updateUserEmail").value = data.user_email;

                    })
                    .catch(error => {
                        console.error('Error:', error.message);
                    })

                fetch("/role")
                    .then(response => response.json())
                    .then(statuses => {
                        updateRol.innerHTML = "";  // Limpiar las opciones antes de añadirlas

                        statuses.forEach(role => {
                            // Crear el elemento option
                            const option = document.createElement('option');
                            option.value = role.id;
                            option.textContent = role.role_name;
                            if (role.id == role_id) {
                                option.selected = true;
                            }
                            // Añadir la opción al select
                            updateRol.appendChild(option);
                        });
                    });
                updateModal.classList.remove("hidden");
            })
        });
    });

    //Abrir modal nuevo usuario

    openNewUser.addEventListener("click", () => {
        openUserModal.classList.remove("hidden");
    })

    //Cerrar modal nuevo usuario
    closeNewUser.addEventListener("click", () => {
        openUserModal.classList.add("hidden");
    })

    //Cerrar modal nuevo usuario
    closeUpdate.addEventListener("click", () => {
        updateModal.classList.add("hidden");
    })

    const actionMenus = document.querySelectorAll('.action-menu');

    actionMenus.forEach(menu => {
        const dots = menu.querySelector('.action-dots');
        const dropdown = menu.querySelector('.action-dropdown');

        dots.addEventListener('click', () => {
            // Oculta otros menús abiertos
            document.querySelectorAll('.action-dropdown').forEach(drop => {
                if (drop !== dropdown) {
                    drop.classList.add('hidden');
                }
            });

            // Alterna el menú actual
            dropdown.classList.toggle('hidden');
        });

        // Cierra el menú al hacer clic fuera
        document.addEventListener('click', (event) => {
            if (!menu.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {

        fetch("/role")
            .then(response => response.json())
            .then(statuses => {
                rolOptions.innerHTML = "";  // Limpiar las opciones antes de añadirlas

                statuses.forEach(role => {
                    // Crear el elemento option
                    const option = document.createElement('option');
                    option.value = role.id;
                    option.textContent = role.role_name;
                    // Añadir la opción al select
                    rolOptions.appendChild(option);
                });
            });
    })

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
