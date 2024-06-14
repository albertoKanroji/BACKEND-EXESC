<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel API</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        /* Tailwind CSS */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #201919;
            color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #ef4444;
        }

        .info {
            font-size: 16px;
            line-height: 1.5;
            color: #6b7280;
        }

        .version {
            margin-top: 20px;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>

<body class="antialiased">
    <div class="container">
        <div class="title">HOLA MUNDO MICHEEE</div>
        <div class="info">
            <p>Bienvenido a la API de nuestra aplicación. Todo está funcionando correctamente.</p>
            <p>Puedes utilizar los endpoints documentados para interactuar con nuestra API.</p>
            <p>Si necesitas ayuda, consulta la documentación o contacta con el soporte técnico.</p>
        </div>
        <div class="version">
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
        </div>
    </div>
</body>

</html>