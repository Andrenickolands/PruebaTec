<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Usuarios</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #3B234A, #523961);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .dashboard {
            padding: 30px;
        }

        .controls {
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .control-group label {
            font-weight: bold;
            color: #3B234A;
            font-size: 0.9rem;
        }

        select, button {
            padding: 10px 15px;
            border: 2px solid #C3BBC9;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        select:focus {
            outline: none;
            border-color: #523961;
            box-shadow: 0 0 0 3px rgba(82, 57, 97, 0.1);
        }

        button {
            background: linear-gradient(135deg, #3B234A, #523961);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 35, 74, 0.3);
        }

        .export-buttons {
            display: flex;
            gap: 10px;
        }

        .export-buttons button {
            flex: 1;
            min-width: 100px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-container {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-5px);
        }

        .chart-title {
            text-align: center;
            margin-bottom: 20px;
            color: #3B234A;
            font-size: 1.3rem;
            font-weight: bold;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            border-left: 5px solid;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .stat-card.purple { border-left-color: #3B234A; }
        .stat-card.gray { border-left-color: #6E6E6E; }
        .stat-card.coral { border-left-color: #EF7575; }
        .stat-card.sage { border-left-color: #B7C9A9; }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        @media (max-width: 768px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .controls {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2rem;
            }

            .export-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Dashboard de Usuarios</h1>
            <p>Análisis de datos por país, departamento y edades</p>
        </div>

        <div class="dashboard">
            <div class="controls">
                <div class="control-group">
                    <label>Filtrar por País:</label>
                    <select id="paisFilter">
                        <option value="">Cargando países...</option>
                    </select>
                </div>
                <div class="control-group">
                    <label>Filtrar por Departamento:</label>
                    <select id="deptoFilter" disabled>
                        <option value="">Seleccione un país primero</option>
                    </select>
                </div>
                <div class="control-group">
                    <label>Acciones:</label>
                    <button onclick="actualizarGraficos()">🔄 Actualizar</button>
                </div>
                <div class="control-group">
                    <label>Exportar Datos:</label>
                    <div class="export-buttons">
                        <button onclick="exportarCSV()">📄 CSV</button>
                        <button onclick="exportarExcel()">📊 Excel</button>
                        <button onclick="exportarPDF()">📋 PDF</button>
                    </div>
                </div>
            </div>

            <div class="stats-grid" id="statsGrid">
                <div class="stat-card purple">
                    <div class="stat-number" id="totalUsuarios">0</div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
                <div class="stat-card gray">
                    <div class="stat-number" id="totalPaises">0</div>
                    <div class="stat-label">Países</div>
                </div>
                <div class="stat-card coral">
                    <div class="stat-number" id="totalDeptos">0</div>
                    <div class="stat-label">Departamentos</div>
                </div>
                <div class="stat-card sage">
                    <div class="stat-number" id="edadPromedio">0</div>
                    <div class="stat-label">Edad Promedio</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-container">
                    <div class="chart-title">Usuarios por País</div>
                    <canvas id="chartPais"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-title">Usuarios por Departamento</div>
                    <canvas id="chartDepto"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-title">Distribución por Edades</div>
                    <canvas id="chartEdad"></canvas>
                </div>
                <div class="chart-container">
                    <div class="chart-title">Estado Civil</div>
                    <canvas id="chartEstadoCivil"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Colores del diseño
        const colores = [
            '#3B234A', '#523961', '#674D69', '#C3BBC9', 
            '#EFEFEF', '#B7C9A9', '#6E6E6E', '#9A9A9A', '#EF7575'
        ];

        // Datos de ejemplo solo para edades y estados civiles
        let usuarios = [
            {id: 1, nombres: 'Usuario', apellidos: '1', pais: '', departamento: '', edad: 25, estado_civil: 'Soltero'},
            {id: 2, nombres: 'Usuario', apellidos: '2', pais: '', departamento: '', edad: 32, estado_civil: 'Casado'},
            {id: 3, nombres: 'Usuario', apellidos: '3', pais: '', departamento: '', edad: 28, estado_civil: 'Soltero'},
            {id: 4, nombres: 'Usuario', apellidos: '4', pais: '', departamento: '', edad: 35, estado_civil: 'Divorciado'},
            {id: 5, nombres: 'Usuario', apellidos: '5', pais: '', departamento: '', edad: 42, estado_civil: 'Casado'},
            {id: 6, nombres: 'Usuario', apellidos: '6', pais: '', departamento: '', edad: 29, estado_civil: 'Soltero'},
            {id: 7, nombres: 'Usuario', apellidos: '7', pais: '', departamento: '', edad: 31, estado_civil: 'Casado'},
            {id: 8, nombres: 'Usuario', apellidos: '8', pais: '', departamento: '', edad: 26, estado_civil: 'Soltero'}
        ];

        let charts = {};
        let usuariosFiltrados = [...usuarios];
        let paisesDisponibles = [];

        // Cargar países y ciudades desde la API
        document.addEventListener("DOMContentLoaded", async () => {
            await cargarPaises();
            inicializar();
        });

        async function cargarPaises() {
            const paisSelect = document.getElementById("paisFilter");
            const depSelect = document.getElementById("deptoFilter");

            try {
                const resp = await fetch("https://countriesnow.space/api/v0.1/countries/positions");
                const json = await resp.json();

                if (!json || !Array.isArray(json.data)) {
                    throw new Error("Respuesta inesperada de la API");
                }

                paisesDisponibles = json.data.sort((a, b) => a.name.localeCompare(b.name));
                
                paisSelect.innerHTML = '<option value="">Todos los países</option>';
                paisesDisponibles.forEach(country => {
                    const option = document.createElement("option");
                    option.value = country.name;
                    option.textContent = country.name;
                    paisSelect.appendChild(option);
                });

                // Event listener para cargar ciudades
                paisSelect.addEventListener("change", async (e) => {
                    const country = e.target.value;
                    
                    if (!country) {
                        depSelect.innerHTML = '<option value="">Todos los departamentos</option>';
                        depSelect.disabled = false;
                        return;
                    }

                    depSelect.innerHTML = '<option value="">Cargando...</option>';
                    depSelect.disabled = true;

                    try {
                        const resp = await fetch("https://countriesnow.space/api/v0.1/countries/cities", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ country })
                        });

                        const data = await resp.json();
                        depSelect.innerHTML = '<option value="">Todos los departamentos</option>';

                        if (data.data && Array.isArray(data.data)) {
                            data.data.sort().forEach(city => {
                                const option = document.createElement("option");
                                option.value = city;
                                option.textContent = city;
                                depSelect.appendChild(option);
                            });
                        }
                        depSelect.disabled = false;
                    } catch (err) {
                        console.error("Error al cargar ciudades:", err);
                        depSelect.innerHTML = '<option value="">Error al cargar</option>';
                        depSelect.disabled = false;
                    }
                });

            } catch (err) {
                console.error("Error al cargar países:", err);
                paisSelect.innerHTML = '<option value="">Error al cargar países</option>';
            }
        }

        function inicializar() {
            actualizarEstadisticas();
            crearGraficos();
        }

        function actualizarGraficos() {
            const paisSeleccionado = document.getElementById('paisFilter').value;
            const deptoSeleccionado = document.getElementById('deptoFilter').value;

            usuariosFiltrados = usuarios.filter(usuario => {
                return (!paisSeleccionado || usuario.pais === paisSeleccionado) &&
                       (!deptoSeleccionado || usuario.departamento === deptoSeleccionado);
            });

            actualizarEstadisticas();
            actualizarCharts();
        }

        function actualizarEstadisticas() {
            const totalUsuarios = usuariosFiltrados.length;
            const paisesUnicos = paisSeleccionado ? 1 : paisesDisponibles.length;
            const deptosUnicos = deptoSeleccionado ? 1 : new Set(usuariosFiltrados.map(u => u.departamento)).size;
            const edadPromedio = usuariosFiltrados.length > 0 ? 
                Math.round(usuariosFiltrados.reduce((sum, u) => sum + parseInt(u.edad), 0) / usuariosFiltrados.length) : 0;

            document.getElementById('totalUsuarios').textContent = totalUsuarios;
            document.getElementById('totalPaises').textContent = paisesUnicos;
            document.getElementById('totalDeptos').textContent = deptosUnicos;
            document.getElementById('edadPromedio').textContent = edadPromedio;
        }

        function crearGraficos() {
            // Gráfico por País (mostrando solo países con usuarios)
            const ctxPais = document.getElementById('chartPais').getContext('2d');
            const datosPais = contarPor('pais');
            charts.pais = new Chart(ctxPais, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(datosPais).length ? Object.keys(datosPais) : ['Sin datos'],
                    datasets: [{
                        data: Object.keys(datosPais).length ? Object.values(datosPais) : [1],
                        backgroundColor: colores
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Gráfico por Departamento
            const ctxDepto = document.getElementById('chartDepto').getContext('2d');
            const datosDepto = contarPor('departamento');
            charts.departamento = new Chart(ctxDepto, {
                type: 'bar',
                data: {
                    labels: Object.keys(datosDepto).length ? Object.keys(datosDepto) : ['Sin datos'],
                    datasets: [{
                        label: 'Usuarios',
                        data: Object.keys(datosDepto).length ? Object.values(datosDepto) : [0],
                        backgroundColor: colores[0],
                        borderColor: colores[1],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico por Edad
            const ctxEdad = document.getElementById('chartEdad').getContext('2d');
            const datosEdad = agruparEdades();
            charts.edad = new Chart(ctxEdad, {
                type: 'line',
                data: {
                    labels: Object.keys(datosEdad),
                    datasets: [{
                        label: 'Cantidad',
                        data: Object.values(datosEdad),
                        borderColor: colores[8],
                        backgroundColor: colores[8] + '20',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            // Gráfico Estado Civil
            const ctxEstado = document.getElementById('chartEstadoCivil').getContext('2d');
            const datosEstado = contarPor('estado_civil');
            charts.estado = new Chart(ctxEstado, {
                type: 'pie',
                data: {
                    labels: Object.keys(datosEstado),
                    datasets: [{
                        data: Object.values(datosEstado),
                        backgroundColor: [colores[5], colores[6], colores[7], colores[2]]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        function contarPor(campo) {
            const conteo = {};
            usuariosFiltrados.forEach(usuario => {
                const valor = usuario[campo] || 'No especificado';
                conteo[valor] = (conteo[valor] || 0) + 1;
            });
            return conteo;
        }

        function agruparEdades() {
            const grupos = {
                '18-25': 0, '26-30': 0, '31-35': 0, '36-40': 0, '41+': 0
            };
            
            usuariosFiltrados.forEach(usuario => {
                const edad = parseInt(usuario.edad);
                if (edad <= 25) grupos['18-25']++;
                else if (edad <= 30) grupos['26-30']++;
                else if (edad <= 35) grupos['31-35']++;
                else if (edad <= 40) grupos['36-40']++;
                else grupos['41+']++;
            });
            
            return grupos;
        }

        function actualizarCharts() {
            // Actualizar datos de todos los gráficos
            const datosPais = contarPor('pais');
            charts.pais.data.labels = Object.keys(datosPais).length ? Object.keys(datosPais) : ['Sin datos'];
            charts.pais.data.datasets[0].data = Object.keys(datosPais).length ? Object.values(datosPais) : [1];
            charts.pais.update();

            const datosDepto = contarPor('departamento');
            charts.departamento.data.labels = Object.keys(datosDepto).length ? Object.keys(datosDepto) : ['Sin datos'];
            charts.departamento.data.datasets[0].data = Object.keys(datosDepto).length ? Object.values(datosDepto) : [0];
            charts.departamento.update();

            const datosEdad = agruparEdades();
            charts.edad.data.labels = Object.keys(datosEdad);
            charts.edad.data.datasets[0].data = Object.values(datosEdad);
            charts.edad.update();

            const datosEstado = contarPor('estado_civil');
            charts.estado.data.labels = Object.keys(datosEstado);
            charts.estado.data.datasets[0].data = Object.values(datosEstado);
            charts.estado.update();
        }

        function exportarCSV() {
            const headers = ['ID', 'Nombres', 'Apellidos', 'País', 'Departamento', 'Edad', 'Estado Civil'];
            const csvContent = [
                headers.join(','),
                ...usuariosFiltrados.map(u => 
                    `${u.id},"${u.nombres}","${u.apellidos}","${u.pais}","${u.departamento}",${u.edad},"${u.estado_civil}"`
                )
            ].join('\n');

            descargarArchivo(csvContent, `usuarios_${new Date().getTime()}.csv`, 'text/csv');
        }

        function exportarExcel() {
            const ws = XLSX.utils.json_to_sheet(usuariosFiltrados.map(u => ({
                'ID': u.id,
                'Nombres': u.nombres,
                'Apellidos': u.apellidos,
                'País': u.pais,
                'Departamento': u.departamento,
                'Edad': u.edad,
                'Estado Civil': u.estado_civil
            })));

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Usuarios');
            
            XLSX.writeFile(wb, `usuarios_${new Date().getTime()}.xlsx`);
        }

        function exportarPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(20);
            doc.text('Dashboard de Usuarios', 20, 30);
            
            doc.setFontSize(12);
            doc.text(`Total de usuarios: ${usuariosFiltrados.length}`, 20, 50);
            doc.text(`Fecha de exportación: ${new Date().toLocaleDateString()}`, 20, 60);

            let yPos = 80;
            doc.setFontSize(14);
            doc.text('Lista de Usuarios:', 20, yPos);
            
            yPos += 10;
            doc.setFontSize(10);
            usuariosFiltrados.slice(0, 30).forEach((usuario, index) => {
                if (yPos > 270) {
                    doc.addPage();
                    yPos = 30;
                }
                doc.text(`${index + 1}. ${usuario.nombres} ${usuario.apellidos} - Edad: ${usuario.edad} - Estado: ${usuario.estado_civil}`, 20, yPos);
                yPos += 10;
            });

            if (usuariosFiltrados.length > 30) {
                doc.text(`... y ${usuariosFiltrados.length - 30} usuarios más`, 20, yPos);
            }

            doc.save(`usuarios_${new Date().getTime()}.pdf`);
        }

        function descargarArchivo(contenido, nombreArchivo, tipo) {
            const blob = new Blob([contenido], { type: tipo });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = nombreArchivo;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>