<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminders - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('css/reminders.css')}}">
</head>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<body>
<div class="container">
    <x-lateral-bar></x-lateral-bar>

    <main class="main-content">
        <header class="header">
            <h1>RECORDATORIOS</h1>
        </header>
        @isset($reminders)
            @foreach($reminders as $reminder)
                <!-- Lista de recordatorios -->
                <div class="table-container">
                    <div class="modal-content">
                        <h2>{{$reminder->title}}  <i class="fas fa-laptop-code menu-icon"></i></h2>
                        <br>
                        <label><h3>DESCRIPCIÓN:</h3> {{$reminder->description}}<br></label>
                        <br>
                        <label><h3>ID DE PROYECTO:</h3> {{$reminder->reminder_project_id}}<br></label>
                        <br>
                        <label><h3>FECHA:</h3> {{$reminder->reminder_date}}</label>
                        <br><br><br>
                        <form method="POST" action="{{route('reminder-delete',$reminder->id)}}">
                            @csrf @method('DELETE')
                            <button class="button">Marcar como completado</button>
                        </form>
                        <br>
                    </div>
                </div>
            @endforeach
        @endisset
    </main>
</div>

<!-- Script para manejar recordatorios -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Inicializar el selector de fechas con Flatpickr
        flatpickr(".datepicker", { enableTime: true, dateFormat: "Y-m-d H:i" });

        // Manejar el formulario de recordatorios
        document.getElementById("reminderForm").addEventListener("submit", function (event) {
            event.preventDefault();

            // Capturar datos del formulario
            const title = document.getElementById("title").value;
            const date = document.getElementById("date").value;
            const type = document.getElementById("type").value;
            const message = document.getElementById("message").value;

            // Crear un nuevo recordatorio en la lista
            const reminderList = document.getElementById("reminderList");
            const li = document.createElement("li");
            li.innerHTML = `<strong>${title}</strong> - ${date} (${type}) <p>${message}</p>`;
            reminderList.appendChild(li);

            // Limpiar formulario
            this.reset();
        });
    });
</script>
</body>
</html>
