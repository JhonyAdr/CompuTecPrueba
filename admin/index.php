<?php
session_start();
require_once '../config/database.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Obtener estadísticas
$stats = [
    'total_usuarios' => $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn(),
    'total_productos' => $conn->query("SELECT COUNT(*) FROM productos")->fetchColumn(),
    'total_transacciones' => $conn->query("SELECT COUNT(*) FROM transacciones")->fetchColumn(),
    'total_categorias' => $conn->query("SELECT COUNT(*) FROM categorias")->fetchColumn()
];

// Obtener últimos usuarios registrados
$stmt = $conn->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC LIMIT 5");
$ultimos_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener últimos productos
$stmt = $conn->query("SELECT p.*, u.nombre as vendedor_nombre 
                     FROM productos p 
                     JOIN usuarios u ON p.id_vendedor = u.id_usuario 
                     ORDER BY p.fecha_publicacion DESC LIMIT 5");
$ultimos_productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - CompuTec Marketplace</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: rgba(30, 30, 30, 0.95);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
        .stat-card h3 {
            font-size: 2em;
            margin: 10px 0;
            color: var(--primary-color);
        }
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
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <nav class="nav">
                <div class="logo">
                    <h1>Panel de Administración</h1>
                </div>
                <div class="nav-links">
                    <a href="../index.php">Volver al Sitio</a>
                    <a href="../logout.php">Cerrar Sesión</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="admin-menu">
            <a href="usuarios.php">Gestionar Usuarios</a>
            <a href="productos.php">Gestionar Productos</a>
            <a href="categorias.php">Gestionar Categorías</a>
            <a href="transacciones.php">Ver Transacciones</a>
            <a href="reportes.php">Reportes</a>
        </div>

        <div class="admin-grid">
            <div class="stat-card">
                <h4>Total Usuarios</h4>
                <h3><?php echo $stats['total_usuarios']; ?></h3>
            </div>
            <div class="stat-card">
                <h4>Total Productos</h4>
                <h3><?php echo $stats['total_productos']; ?></h3>
            </div>
            <div class="stat-card">
                <h4>Total Transacciones</h4>
                <h3><?php echo $stats['total_transacciones']; ?></h3>
            </div>
            <div class="stat-card">
                <h4>Total Categorías</h4>
                <h3><?php echo $stats['total_categorias']; ?></h3>
            </div>
        </div>

        <div class="admin-section">
            <h2>Últimos Usuarios Registrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Fecha Registro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ultimos_usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id_usuario']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td><?php echo ucfirst($usuario['tipo_usuario']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($usuario['fecha_registro'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-section">
            <h2>Últimos Productos</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Vendedor</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ultimos_productos as $producto): ?>
                        <tr>
                            <td><?php echo $producto['id_producto']; ?></td>
                            <td><?php echo htmlspecialchars($producto['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($producto['vendedor_nombre']); ?></td>
                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                            <td><?php echo ucfirst($producto['estado']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($producto['fecha_publicacion'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> CompuTec Marketplace. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html> 