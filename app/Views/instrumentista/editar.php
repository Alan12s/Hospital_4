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

    <form action="<?= base_url('/instrumentista/actualizar/' . $instrumentista['id']) ?>" method="post">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?= esc($instrumentista['nombre']) ?>"><br>

        <label>Apellido:</label><br>
        <input type="text" name="apellido" value="<?= esc($instrumentista['apellido']) ?>"><br>

        <label>Especialidad:</label><br>
        <input type="text" name="especialidad" value="<?= esc($instrumentista['especialidad']) ?>"><br>

        <button type="submit">Actualizar</button>
    </form>

    <br>
    <a href="<?= base_url('/instrumentista') ?>">⬅️ Volver</a>
</body>
</html>
