<?php
// Datos de conexión
$servidor = "localhost";
$usuario = "root";
$password = "";

// Conectar a MySQL
$conn = new mysqli($servidor, $usuario, $password);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Crear base de datos
$sql = "CREATE DATABASE IF NOT EXISTS biblioteca";
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada exitosamente.<br>";
} else {
    echo "Error al crear la base de datos: " . $conn->error;
}

// Seleccionar la base de datos
$conn->select_db("biblioteca");

// Crear tabla
$sql = "CREATE TABLE IF NOT EXISTS registros_biblioteca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    semestre VARCHAR(20),
    hora_entrada TIME,
    motivo VARCHAR(50),
    turno ENUM('matutino', 'vespertino'),
    hora_salida TIME,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
    echo "Tabla creada exitosamente.";
} else {
    echo "Error al crear la tabla: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>
