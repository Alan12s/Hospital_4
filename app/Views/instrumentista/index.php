<!DOCTYPE html>
<html>
<head>
    <title><?= $titulo ?></title>
</head>
<body>
    <h1><?= $titulo ?></h1>

    <?php if (session()->getFlashdata('mensaje')): ?>
        <p style="color: green;"><?= session()->getFlashdata('mensaje') ?></p>
    <?php endif; ?>

    <a href="<?= base_url('/instrumentista/crear') ?>">➕ Agregar nuevo instrumentista</a>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Especialidad</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($instrumentistas as $i): ?>
            <tr>
                <td><?= $i['id'] ?></td>
                <td><?= esc($i['nombre']) ?></td>
                <td><?= esc($i['apellido']) ?></td>
                <td><?= esc($i['especialidad']) ?></td>
                <td>
                    <a href="<?= base_url('/instrumentista/editar/' . $i['id']) ?>">✏️ Editar</a> |
                    <a href="<?= base_url('/instrumentista/eliminar/' . $i['id']) ?>" onclick="return confirm('¿Eliminar este instrumentista?')">🗑️ Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
