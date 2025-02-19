<!DOCTYPE html>
<html>
<head>
    <title>Cambios en el Restaurante - The Fork</title>
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #02665D;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .header h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }
        .content {
            background: white;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            color: #02665D;
            font-size: 20px;
            margin-top: 20px;
            border-bottom: 2px solid #019688;
            padding-bottom: 10px;
        }
        .changes-list, .current-data {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .changes-list li, .current-data li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .changes-list li:last-child, .current-data li:last-child {
            border-bottom: none;
        }
        .label {
            color: #02665D;
            font-weight: 600;
            display: inline-block;
            width: 120px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #7f8c8d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cambios en el Restaurante - The Fork</h1>
    </div>
    
    <div class="content">
        <h2>Se han realizado cambios en el restaurante {{ $restaurante->nombre_r }}</h2>

        <h2>Datos actuales del restaurante:</h2>
        <ul class="current-data">
            <li><span class="label">Nombre:</span> {{ $restaurante->nombre_r }}</li>
            <li><span class="label">Dirección:</span> {{ $restaurante->direccion ?? 'No especificada' }}</li>
            <li><span class="label">Municipio:</span> {{ $restaurante->municipio ?? 'No especificado' }}</li>
            <li><span class="label">Precio promedio:</span> {{ $restaurante->precio_promedio }}€</li>
            <li><span class="label">Tipo de cocina:</span> {{ $restaurante->tipoCocina->nombre }}</li>
        </ul>

        <div class="footer">
            <p>Por favor, revisa los cambios realizados en el sistema.</p>
            <p>Equipo The Fork</p>
        </div>
    </div>
</body>
</html> 