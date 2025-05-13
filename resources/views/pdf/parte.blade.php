<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parte Reparación #{{ $parte->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td, th { padding: 6px; border: 1px solid #ccc; }
        .imagenes img { margin: 10px; border: 1px solid #999; max-height: 200px; }
    </style>
</head>
<body>
    <h1>Parte de Reparación</h1>

    <table>
        <tr><th>Cliente</th><td>{{ $parte->cliente->nombre }}</td></tr>
        <tr><th>DNI/CIF</th><td>{{ $parte->cliente->dni_cif }}</td></tr>
        <tr><th>Equipo</th><td>{{ $parte->nombre_equipo }}</td></tr>
        <tr><th>Problema</th><td>{{ $parte->problema }}</td></tr>
        <tr><th>Solución</th><td>{{ $parte->solucion_aplicada }}</td></tr>
        <tr><th>Estado</th><td>{{ ucfirst($parte->estado) }}</td></tr>
        <tr><th>Entrada</th><td>{{ $parte->fecha_entrada }}</td></tr>
        <tr><th>Salida</th><td>{{ $parte->fecha_salida }}</td></tr>
        <tr><th>Precio Estimado</th><td>{{ number_format($parte->precio_estimado, 2) }} €</td></tr>
        <tr><th>Precio Final</th><td>{{ number_format($parte->precio_final, 2) }} €</td></tr>
    </table>

    <p><strong>Observaciones:</strong> {{ $parte->observaciones }}</p>

    @if (!empty($parte->imagenes))
        <h2>Imágenes del equipo</h2>
        <div class="imagenes">
            @foreach ($parte->imagenes as $imagen)
            <img src="{{ public_path($imagen) }}" alt="Imagen del equipo" style="max-height: 200px;">


            @endforeach
        </div>
    @endif
</body>
</html>

