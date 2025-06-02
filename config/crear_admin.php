<?php
require_once 'database.php';

try {
    // Verificar si el usuario admin ya existe
    $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $stmt->execute(['admin@lunitas.com']);
    
    if (!$stmt->fetch()) {
        // Si no existe, crear el usuario admin
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, tipo_usuario, estado, fecha_registro) VALUES (?, ?, ?, 'admin', 1, NOW())");
        $password_hash = password_hash('jhonadr159', PASSWORD_DEFAULT);
        $stmt->execute(['Administrador', 'admin@lunitas.com', $password_hash]);
        echo "Usuario administrador creado exitosamente.";
    } else {
        echo "El usuario administrador ya existe.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 