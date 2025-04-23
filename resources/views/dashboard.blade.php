<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DM Solutions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
</head>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<body>
<div class="container">
    <x-lateral-bar></x-lateral-bar>

    <!-- Contenido principal (donde estarán los gráficos) -->
    <div class="main-content">
        <header class="header">
            <h1>DASHBOARD - PROYECTOS</h1>
        </header>
        <div class="table-container">
            <table class="project-table">
                <thead>
                <tr>
                    <th>TOTAL DE PROYECTOS: </th>
                    <th id="totalProjects"></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <br>
        <div class="grid-container">
            <!-- Gráfico 1: Estado de Proyectos -->
            <div class="chart-container">
                <h2>Proyectos por Estado</h2>
                <br>
                <canvas id="projectStatusChart1"></canvas>
            </div>

            <!-- Gráfico 2: Proyectos por Cliente -->
            <div class="chart-container">
                <h2>Proyectos por Cliente</h2>
                <br>
                <canvas id="projectStatusChart2"></canvas>
            </div>
            <!-- Gráfico 3: Proyectos por Fecha -->
            <div class="chart-container">
                <h2>Proyectos por Mes</h2>
                <br>
                <canvas style="margin: 10%" id="projectStatusChart3"></canvas>
            </div>
            <!-- Gráfico 4: Proyectos por Anticipo -->
            <div class="chart-container">
                <h2>Cotizaciones sin proyecto</h2>
                <br>
                <canvas style="margin: 10%" id="projectStatusChart4"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    let keys_status = []
    let value_status = []
    let keys_client = []
    let value_client = []
    let keys_month = []
    let value_month = []
    // Función para crear gráfico de torta
    function createBarChart(ctx, labels, data,label) {
        const maxValue = Math.max(...Object.values(data)) + 3;
        console.log(maxValue,"Soy el maximoooo")
            return new Chart(ctx, {
                type: 'bar',  // Tipo de gráfico
                data: {
                    labels: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'], // Etiquetas en el eje X
                    datasets: [{
                        label: label,
                        data: data,  // Datos
                        backgroundColor: 'rgba(36, 191, 164, 0.6)',  // Color de barras
                        borderColor: 'rgba(54, 162, 235, 1)',  // Borde de barras
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: maxValue,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

    function createPieChart(ctx, labels, data, backgroundColor) {
        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColor
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }

        const projectData3 = {
            "Enero":0,
            "Febrero": 0,
            "Marzo":0,
            "Abril":0,
            "Mayo":0,
            "Junio":0,
            "Julio":0,
            "Agosto":0,
            "Septiembre":0,
            "Octubre":0,
            "Noviembre":0,
            "Diciembre":0
        };

    const projectData4 = {
        "Enero":0,
        "Febrero": 0,
        "Marzo":0,
        "Abril":0,
        "Mayo":0,
        "Junio":0,
        "Julio":0,
        "Agosto":0,
        "Septiembre":0,
        "Octubre":0,
        "Noviembre":0,
        "Diciembre":0
    };
    window.onload = function() {
         //Proyectos por estado
        fetch("/dashboard/status")
            .then(response => response.json())
            .then(data => {
                console.log(data)
                keys_status = data.map(item => item.status_name);
                console.log("CANTIDAAAAAAAAAAAD: ", keys_status.length)
                document.getElementById("totalProjects").textContent = keys_status.length
                console.log ("labels",keys_status);
                value_status = data.map(item => item.cantidad);
                console.log ("Values",value_status);
                createPieChart(
                    document.getElementById('projectStatusChart1').getContext('2d'),
                    keys_status,
                    value_status,
                    ['rgba(36, 191, 164, 0.6)', 'rgba(153, 102, 255, 0.6)','rgba(255, 159, 64, 0.6)', 'rgba(255, 99, 132, 0.6)']
                );
            })

            //proyectos por cliente

        fetch("/dashboard/clients")
            .then(response => response.json())
            .then(data => {
                keys_client = data.map(item => item.client_name);
                console.log ("labels",keys_status);
                value_client = data.map(item => item.cantidad);
                console.log ("Values",value_status);
                createPieChart(
                    document.getElementById('projectStatusChart2').getContext('2d'),
                    keys_client,
                    value_client,
                    ['rgba(36, 191, 164, 0.6)', 'rgba(153, 102, 255, 0.6)','rgba(255, 159, 64, 0.6)', 'rgba(255, 99, 132, 0.6)']
                );
            })

        //proyectos por mes
        fetch("/dashboard/month")
            .then(response => response.json())
            .then(data => {
                keys_month = data.map(item => item.month);
                console.log ("labels Month",keys_month);
                value_month = data.map(item => item.cantidad);
                console.log ("Values Month",value_month);
                const keys = Object.keys(projectData3);
                data.forEach(r => {
                    projectData3[keys[r.month-1]]=r.cantidad;
                })
                createBarChart(
                    document.getElementById('projectStatusChart3').getContext('2d'),
                    Object.keys(projectData3),
                    Object.values(projectData3),
                    "Proyectos por mes"
                );
            })

        //cotización sin proyectos por mes

        fetch("/dashboard/quotes")
            .then(response => response.json())
            .then(data => {
                console.log(data);
                keys_month = data.map(item => item.mes);
                console.log ("labels Month quotes",keys_month);
                value_month = data.map(item => item.cantidad);
                console.log ("Values Month quotes",value_month);
                const keys = Object.keys(projectData4);
                data.forEach(r => {
                    projectData4[keys[r.mes-1]]=r.cantidad;
                })
                createBarChart(
                    document.getElementById('projectStatusChart4').getContext('2d'),
                    Object.keys(projectData4),
                    Object.values(projectData4),
                    "Cotizaciones sin proyecto por mes"
                );
            })
        }
    </script>
</body>
</html>
