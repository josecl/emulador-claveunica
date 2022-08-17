<html lang="es">
<head>
    <title>ClaveÚnica</title>
    <style>
        input { font-size: 1.2rem; padding: 0.5rem; margin-top: 12px; border: 1px solid #aaa; border-radius: 3px; width: 100% }
        input[type="submit"] { cursor: pointer; background-color: #0F69C4; color: white; border: 1px solid #093F75; border-radius: 3px }
        input[type="submit"]:hover { background-color: #093F75; }
        input:invalid { border-color: rgb(226, 44, 44); }
        input:invalid + label {color: rgb(226, 44, 44);}
        form > div {
            position: relative;
        }
        form label {
            position: absolute;
            display: inline-block;
            top: -2px;
            bottom: 0;
            left: 10px;
            height: 3px;
            transition: 0.2s;
            font-size: 0.6rem;
            color: #666;
        }


    </style>
</head>
<body style="background: #0F69C4; background: linear-gradient(160deg, rgba(15,80,145,1) 0%, rgba(15,105,196,1) 19%, rgba(15,105,196,1) 84%, rgba(12,84,156,1) 100%);
  font-family: sans-serif;  display: flex; justify-content: center; align-items: center">
<form id="form" method="post" action="{{ route('emulador-claveunica.authorize-action') }}"
      style="min-width: 320px; padding: 40px; background-color: white; border: 1px solid rgba(200, 200, 200, 1); box-shadow: rgba(60, 60, 60, 0.4) 0 2px 8px 0; border-radius: 6px; display: flex; flex-direction: column; gap: 10px;">
        <h2 style="margin-top: 0">Emulador de ClaveÚnica</h2>

    <div>
        <input name="rut" type="text" placeholder="RUN" value="{{ $rut }}" style="max-width: 160px" required minlength="3" maxlength="12" pattern="\d[\d\.]{1,9}-[\dkK]" title="Debes ingresar un RUT válido">
        <label for="rut">RUN</label>
    </div>

    <div>
        <input name="nombres" type="text" placeholder="Nombres" value="{{ $nombres }}" required>
        <label for="nombres">Nombres</label>
    </div>

    <div>
        <label for="apellidos">Apellidos</label>
        <input name="apellidos" type="text" placeholder="Apellidos" value="{{ $apellidos }}" required>
    </div>


    <input type="submit" value="Iniciar sesión">

    @foreach ($hidden as $name => $value)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    @endforeach
</form>
</body>
</html>
