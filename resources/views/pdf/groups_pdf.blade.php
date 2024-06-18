<!DOCTYPE html>
<html>

<head>
    <title>Grupos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .header-title {
            font-weight: bold;
        }

        .header-info {
            font-weight: normal;
        }

        .institution,
        .period {
            display: inline-block;
            margin-right: 20px;
        }
    </style>
</head>

<body>
    <header>
        <p class="header-title">INSTITUTO TECNOLÓGICO DE LÁZARO CÁRDENAS.</p>
        <p class="header-title">Subdirección de Planeación y Vinculación</p>
        <p class="header-title">Departamento de Actividades Extraescolares</p>
        <p class="header-title">Oficina de Promoción: <span class="header-info">{{ $activity_name }}</span></p>

        <p class="header-title">INFORME SEMESTRAL</p>
        <p class="header-title">Periodo: <span class="header-info">{{ $start_period }} - {{ $end_period }}</span></p>

        <p class="header-title">COMPLETAR CON INFORMACION DE LAS ACTIVIDADES DEL SEMESTRE</p>
    </header>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nombre del Evento</th>
                <th>Institución Organizadora</th>
                <th>Fecha de Realización</th>
                <th>No. de Participantes</th>
                <th>M</th>
                <th>H</th>
                <th>Resultados</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groups as $index => $group)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $group['group_name'] }}</td>
                <td>{{ $group['institution'] }}</td>
                <td>{{ $group['date'] }}</td>
                <td>{{ $group['total_students'] }}</td>
                <td>{{ $group['male_students'] }}</td>
                <td>{{ $group['female_students'] }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
