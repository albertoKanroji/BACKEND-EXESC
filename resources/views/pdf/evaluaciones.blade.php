<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Estudiantes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Lista de Estudiantes</h1>
    <h2>Grupo: {{ $group->activity->name }}</h2>
    @if($students->isEmpty())
    <p>No hay estudiantes en este grupo.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student['id'] }}</td>
                <td>#{{ $student['control_number'] }} - {{ $student['name'] }}</td>
                <td>{{ $student['email'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>


</html>
