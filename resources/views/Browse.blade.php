<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/browse.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- DataTables CSS -->
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
            <h1>PROYECTOS</h1>
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
                    <th>Cliente</th>
                    <th>Precio</th>
                    <th>Anticipo</th>
                    <th>Fecha De Inicio</th>
                    <th>Fecha De cierre</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>

                @isset($projects)
                    @foreach($projects as $project)
                        <tr>
                            <td>{{$project->id}}</td>
                            <td>{{$project->proj_name}}</td>
                            <td><button class="btn">{{number_format($project->quote_total)}}</button></td>
                            <td>{{number_format($project->proj_deposit)}}</td>
                            <td>{{$project->proj_start_date}}</td>
                            <td>{{$project->proj_end_date}}</td>
                            <td>{{$project->status_name}}</td>
                            <td>
                                <div class="action-menu">
                                    <span class="action-dots">•••</span>
                                    <div class="action-dropdown hidden">
                                        <button class="action-btn edit-project">Actualizar</button>
                                        @php
                                            $user = session('user_id') ? DB::table('users')->where('id', session('user_id'))->first() : null;
                                        @endphp

                                        @if ($user && $user->user_role == '1')
                                            <form action="{{route('project-delete',$project->id)}}" method="POST">
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
                <!-- Más filas -->
                </tbody>
            </table>
        </div>
    </main>
</div>
<div class="modal hidden" id="editProjectModal">
    <div class="modal-content">
        <h1>EDITAR PROYECTO: </h1> <br>
        <form id="ProjectForm" method='POST' action="{{route('project-update')}}">
            @csrf @method('PATCH')
            <label for="menu">Estado:</label>
            <select id="menuStatus" name="menuStatus">

            </select>

            <input type="hidden" name="hiddenProjectId" id="hiddenProjectId">
            <label>Visita Técnica: <h style="color: red">*</h></label>
            <input type="text" name="datetimeInput" id="datetimeInput" value="" placeholder="">

            <label>Nombre del proyecto: <h style="color: red">*</h></label>
            <input name="projName" type="text" id="proj-name" required>

            <label>Fecha de inicio: <h style="color: red">*</h></label>
            <input name="projStartDate" type="date" id="proj-start" required>

            <label>Fecha de finalización: <h style="color: red">*</h></label>
            <input name="projEndDate" type="date" id="proj-end" required>

            <label>Anticipo: <h style="color: red">*</h></label>
            <input name="projDeposit" type="number" id="proj-deposit" required>

            <label>Garantía: <h style="color: red">*</h></label>
            <input name="projWarranty" type="date" id="proj-warranty" required>

            <button type="submit" class="modal-button">Actualizar Proyecto</button>
            <button type="button" id="closeEditProjectModal" class="modal-button">Cerrar</button>
        </form>
    </div>
</div>

<script>
    // JavaScript - el menú emergente
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

    //Editar Proyecto
    const EditModalProject = document.getElementById("editProjectModal"); // Selecciona el modal
    const closeEditProjectModal = document.getElementById('closeEditProjectModal'); // Botón de cerrar el modal
    const options = document.getElementById("menuStatus");
    let calendarInstance;

    if(calendarInstance){
        calendarInstance.destroy();
        calendarInstance = null;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const botonesEditar = document.querySelectorAll('.edit-project');
        let status_id = 0;
        botonesEditar.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                // Evitar que el clic en el botón se propague
                event.stopPropagation();
                let fila = boton.closest('tr');
                let projectDetail = fila.cells[0].textContent;
                fetch(`projects/detail/${projectDetail}`)
                    .then(response => response.json())
                    .then(data => {
                        status_id = data.data.status_id;
                        console.log(data);
                        document.getElementById("hiddenProjectId").value = data.data.id;
                        document.getElementById("proj-name").value = data.data.proj_name;
                        document.getElementById("proj-start").value = data.data.proj_start_date;
                        document.getElementById("proj-end").value = data.data.proj_end_date;
                        document.getElementById("proj-deposit").value = data.data.proj_deposit;
                        document.getElementById("proj-warranty").value = data.data.proj_warranty;
                        document.getElementById("proj-deposit").max = fila.cells[2].textContent.replace(/,/g, '');
                        let visita = data.data.proj_visit;

                        document.getElementById("datetimeInput").value = null;
                        if (visita == null){
                            calendarInstance = flatpickr("#datetimeInput", {
                                enableTime: true,         // Permite seleccionar hora
                                dateFormat: "Y-m-d H:i",  // Formato personalizado (YYYY-MM-DD HH:MM)
                                time_24hr: true,
                                defaultDate: null// Formato de 24 horas
                            });

                        }else{
                            calendarInstance = flatpickr("#datetimeInput", {
                                enableTime: true,         // Permite seleccionar hora
                                dateFormat: "Y-m-d H:i",  // Formato personalizado (YYYY-MM-DD HH:MM)
                                time_24hr: true,
                                defaultDate: data.data.proj_visit || null  // Fecha y hora actual como valor por defecto// Formato de 24 horas
                            });
                        }

                    })
                    .catch(error => {
                        console.error('Error:', error.message);
                    })
                fetch("/status")
                    .then(response => response.json())
                    .then(statuses => {
                        options.innerHTML = "";  // Limpiar las opciones antes de añadirlas

                        statuses.forEach(status => {
                            // Crear el elemento option
                            const option = document.createElement('option');
                            option.value = status.id;
                            option.textContent = status.status_name;

                            // Si el estado actual del proyecto coincide con el valor, seleccionar la opción
                            if (status.id == status_id) {
                                option.selected = true;
                            }

                            // Añadir la opción al select
                            options.appendChild(option);
                        });
                    });
                EditModalProject.classList.remove("hidden");
            });
        });
    });

    closeEditProjectModal.addEventListener("click", () => {
        EditModalProject.classList.add("hidden");
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

