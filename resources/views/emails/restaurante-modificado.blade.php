<!DOCTYPE html>
<html>
<head>
    <title>Cambios en tu restaurante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4A5568;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .changes-list {
            list-style: none;
            padding: 0;
        }
        .change-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .field-name {
            color: #4A5568;
            font-weight: bold;
        }
        .change-values {
            margin-top: 5px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Actualización de Restaurante</h1>
    </div>
    
    <div class="content">
        <h2>Se han realizado cambios en {{ $restaurante->nombre_r }}</h2>
        
        <ul class="changes-list">
        @foreach($cambios as $campo => $valores)
            <li class="change-item">
                <div class="field-name">{{ ucfirst($campo) }}</div>
                <div class="change-values">
                    <div>Anterior: {{ $valores['anterior'] }}</div>
                    <div>Nuevo: {{ $valores['nuevo'] }}</div>
                </div>
            </li>
        @endforeach
        </ul>
    </div>
</body>
</html> 