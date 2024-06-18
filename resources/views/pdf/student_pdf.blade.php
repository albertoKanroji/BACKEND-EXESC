<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Información del Estudiante</h1>
    <table>
        <tr>
            <th>Nombre</th>
            <td>{{ $studentData['name'] }}</td>
        </tr>
        <tr>
            <th>Apellido Paterno</th>
            <td>{{ $studentData['apellido_paterno'] }}</td>
        </tr>
        <tr>
            <th>Apellido Materno</th>
            <td>{{ $studentData['apellido_materno'] }}</td>
        </tr>
        <tr>
            <th>Carrera</th>
            <td>{{ $studentData['carrera'] }}</td>
        </tr>
        <tr>
            <th>Número de Control</th>
            <td>{{ $studentData['numero_control'] }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $studentData['status'] }}</td>
        </tr>
        <tr>
            <th>Perfil</th>
            <td>{{ $studentData['perfil'] }}</td>
        </tr>
        <tr>
            <th>Semestre</th>
            <td>{{ $studentData['semestre'] }}</td>
        </tr>
        <tr>
            <th>Género</th>
            <td>{{ $studentData['genero'] }}</td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td>{{ $studentData['telefono'] }}</td>
        </tr>
        <tr>
            <th>Imagen de Perfil</th>
            <td><img src="{{ $studentData['imagen_perfil'] }}" alt="Imagen de Perfil"></td>
        </tr>
    </table>
</body>

</html>
