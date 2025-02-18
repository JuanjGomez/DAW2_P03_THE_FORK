<!DOCTYPE html>
<html>
<head>
    <title>Restaurante Modificado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #2980b9;
            margin-top: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        strong {
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>Se han realizado cambios en el restaurante {{ $restaurante->nombre_r }}</h1>
    
    <h2>Cambios realizados:</h2>
    <ul>
    @foreach($cambios as $campo => $valor)
        @if($campo !== 'updated_at' && $campo !== 'created_at')
            <li>
                <strong>{{ ucfirst($campo) }}:</strong> 
                @if(is_array($valor))
                    {{ json_encode($valor) }}
                @else
                    {{ $valor }}
                @endif
            </li>
        @endif
    @endforeach
    </ul>

    <h2>Datos actuales del restaurante:</h2>
    <ul>
        <li><strong>Nombre:</strong> {{ $restaurante->nombre_r }}</li>
        <li><strong>Dirección:</strong> {{ $restaurante->direccion ?? 'No especificada' }}</li>
        <li><strong>Municipio:</strong> {{ $restaurante->municipio ?? 'No especificado' }}</li>
        <li><strong>Precio promedio:</strong> {{ $restaurante->precio_promedio }}€</li>
        <li><strong>Tipo de cocina:</strong> {{ $restaurante->tipoCocina->nombre }}</li>
    </ul>

    <div class="footer">
        <p>Por favor, revisa los cambios realizados en el sistema.</p>
    </div>
</body>
</html> 