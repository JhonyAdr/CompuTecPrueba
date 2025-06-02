<?php
require_once 'database.php';

try {
    $stmt = $conn->prepare("UPDATE usuarios SET tipo_usuario = 'admin' WHERE email = ?");
    $stmt->execute(['admin@lunitas.com']);
    
    if ($stmt->rowCount() > 0) {
        echo "Usuario actualizado exitosamente a administrador.";
    } else {
        echo "No se encontró el usuario con ese correo electrónico.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 




