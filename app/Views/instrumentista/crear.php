<!DOCTYPE html>
<html>
<head>
    <title><?= $titulo ?></title>
</head>
<body>
    <h1><?= $titulo ?></h1>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('/instrumentista/guardar') ?>" method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= old('nombre') ?>"><br>

        <label>Apellido:</label><br>
        <input type="text" name="apellido" value="<?= old('apellido') ?>"><br>

        <label>Especialidad:</label><br>
        <input type="text" name="especialidad" value="<?= old('especialidad') ?>"><br>

        <button type="submit">Guardar</button>
    </form>

    <br>
    <a href="<?= base_url('/instrumentista') ?>">⬅️ Volver</a>
</body>
</html>
