<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
    
    <?php 
    // COMENTADO: Verificación de autenticación - En CI4 esto se manejaría así:
    // if (!session()->get('logged_in')) {
    //     header('Location: ' . base_url('login'));
    //     exit;
    // }
    // O mejor aún, usando filtros en las rutas
    ?>
</head>
<body>
    <?= $this->renderSection('content') ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>