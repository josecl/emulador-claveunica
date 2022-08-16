<html lang="es">
<head>
    <title>ClaveÚnica</title>
    <style>
        input { font-size: 1.2rem; padding: 0.5rem; border: 1px solid #aaa; border-radius: 3px; }
        input:invalid { border-color: red; }
    </style>
</head>

<body style="font-family: sans-serif; background: #0F69C4; display: flex; justify-content: center; align-items: center">
<form id="form" method="post" action="{{ route('emulador-claveunica.authorize-action') }}"
      style="min-width: 300px; padding: 40px; background-color: white; border: 1px solid rgba(0, 0, 0, 0.3); box-shadow: rgba(60, 60, 60, 0.4) 0 2px 8px 0; border-radius: 6px; display: flex; flex-direction: column; gap: 10px;">
    <div>
        <h2 style="margin-top: 0">Emulador de ClaveÚnica</h2>
    </div>
    <input name="rut" type="text" placeholder="RUN" value="{{ $rut }}" style="max-width: 160px" required minlength="3" maxlength="12" pattern="\d[\d\.]{1,9}-[\dkK]" title="Debes ingresar un RUT válido">
    <input name="nombres" type="text" placeholder="Nombres" value="{{ $nombres }}" required>
    <input name="apellidos" type="text" placeholder="Apellidos" value="{{ $apellidos }}" required>
    <input type="submit" value="Iniciar sesión" style="background-color: #0F69C4; color: white; border: 1px solid #093F75; border-radius: 3px">

    @foreach ($hidden as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
</form>
</body>
</html>
