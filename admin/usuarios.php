<?php
session_start();
require_once '../config/database.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

$error = '';
$success = '';

// Procesar cambios de estado o tipo de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $user_id = (int)$_POST['user_id'];
        
        try {
            switch ($_POST['action']) {
                case 'toggle_status':
                    $stmt = $conn->prepare("UPDATE usuarios SET estado = NOT estado WHERE id_usuario = ?");
                    $stmt->execute([$user_id]);
                    $success = 'Estado del usuario actualizado correctamente.';
                    break;
                    
                case 'change_type':
                    if (isset($_POST['tipo_usuario'])) {
                        $stmt = $conn->prepare("UPDATE usuarios SET tipo_usuario = ? WHERE id_usuario = ?");
                        $stmt->execute([$_POST['tipo_usuario'], $user_id]);
                        $success = 'Tipo de usuario actualizado correctamente.';
                    }
                    break;
            }
        } catch (PDOException $e) {
            $error = 'Error al actualizar el usuario: ' . $e->getMessage();
        }
    }
}

// Obtener todos los usuarios con paginación
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$por_pagina = 10;
$offset = ($pagina - 1) * $por_pagina;

$stmt = $conn->query("SELECT COUNT(*) FROM usuarios");
$total_usuarios = $stmt->fetchColumn();
$total_paginas = ceil($total_usuarios / $por_pagina);

$stmt = $conn->prepare("SELECT * FROM usuarios ORDER BY fecha_registro DESC LIMIT ? OFFSET ?");
$stmt->execute([$por_pagina, $offset]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-section {
            background: rgba(30, 30, 30, 0.95);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .admin-menu {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .admin-menu a {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .admin-menu a:hover {
            background: var(--primary-dark);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .table th {
            background: rgba(0, 0, 0, 0.2);
        }
        .paginacion {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .paginacion a {
            padding: 8px 16px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .paginacion a:hover {
            background: var(--primary-dark);
        }
        .btn-small {
            padding: 5px 10px;
            font-size: 0.9em;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">
                    <h1>Gestión de Usuarios</h1>
                </div>
                <div class="nav-links">
                    <a href="index.php">Volver al Panel</a>
                    <a href="../logout.php">Cerrar Sesión</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="admin-menu">
            <a href="index.php">Panel Principal</a>
            <a href="usuarios.php">Gestionar Usuarios</a>
            <a href="productos.php">Gestionar Productos</a>
            <a href="categorias.php">Gestionar Categorías</a>
            <a href="transacciones.php">Ver Transacciones</a>
            <a href="reportes.php">Reportes</a>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="admin-section">
            <h2>Lista de Usuarios</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id_usuario']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $usuario['id_usuario']; ?>">
                                    <input type="hidden" name="action" value="change_type">
                                    <select name="tipo_usuario" onchange="this.form.submit()">
                                        <option value="admin" <?php echo $usuario['tipo_usuario'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="vendedor" <?php echo $usuario['tipo_usuario'] == 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                                        <option value="comprador" <?php echo $usuario['tipo_usuario'] == 'comprador' ? 'selected' : ''; ?>>Comprador</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $usuario['id_usuario']; ?>">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <button type="submit" class="btn btn-small <?php echo $usuario['estado'] ? 'btn-success' : 'btn-danger'; ?>">
                                        <?php echo $usuario['estado'] ? 'Activo' : 'Inactivo'; ?>
                                    </button>
                                </form>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                            <td>
                                <a href="ver_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-small btn-primary">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="paginacion">
                <?php for($i = 1; $i <= $total_paginas; $i++): ?>
                    <a href="?pagina=<?php echo $i; ?>" 
                       class="<?php echo $i == $pagina ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> CompuTec Marketplace. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html> 