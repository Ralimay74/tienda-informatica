<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Política de Protección de Datos</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; line-height: 1.6; margin: 40px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 20px; }
        p { margin-bottom: 15px; text-align: justify; }
    </style>
</head>
<body>
    <h1>Política de Protección de Datos</h1>

    <p>
        D./Dña. <strong>{{ $cliente->nombre }} {{ $cliente->apellidos }}</strong>,
        con DNI/CIF <strong>{{ $cliente->dni_cif }}</strong>,
        con domicilio en <strong>{{ $cliente->direccion }}, {{ $cliente->cp }} {{ $cliente->localidad }}, {{ $cliente->provincia }} ({{ $cliente->pais }})</strong>,
        declara haber sido informado/a de manera clara y precisa de los términos establecidos en la Ley Orgánica de Protección de Datos y Garantía de Derechos Digitales (LOPDGDD) y en el Reglamento General de Protección de Datos (RGPD).
    </p>

    <p>
        El responsable del tratamiento es esta empresa. Sus datos serán utilizados exclusivamente con la finalidad de prestar el servicio solicitado,
        así como para el cumplimiento de obligaciones legales derivadas.
    </p>

    <p>
        En todo momento podrá ejercer sus derechos de acceso, rectificación, supresión, oposición y demás derechos reconocidos por la normativa vigente,
        dirigiéndose al responsable por correo postal o electrónico.
    </p>

    <p>Fecha: {{ now()->format('d/m/Y') }}</p>

    <p>Firma del cliente: __________________________</p>
</body>
</html>
