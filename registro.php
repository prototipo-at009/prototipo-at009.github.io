<?php
// Incluir la librería PHP QR Code (descargar desde https://sourceforge.net/projects/phpqrcode/)
include('phpqrcode/qrlib.php');

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $semestre = $_POST['semestre'];
    $entrada = $_POST['entrada'];
    $salida = $_POST['salida'];
    $motivo = $_POST['motivo'];
    $turno = $_POST['turno'];

    // Obtener la IP local o nombre de dominio configurado
    $ip_local = '192.168.1.10'; // Cambia por tu IP o nombre de dominio en red local
    $index_url = "http://$ip_local/index.html"; // URL de la página principal

    // Generar el código QR con la URL del formulario
    $qr_filename = "qr_codes/" . uniqid() . ".png";

    // Crear el directorio si no existe
    if (!file_exists('qr_codes')) {
        mkdir('qr_codes', 0777, true);
    }

    // Generar el QR apuntando al archivo index.html
    QRcode::png($index_url, $qr_filename, QR_ECLEVEL_L, 10);

    // Previsualizar el QR antes de guardar en la base de datos
    echo "<h1>Previsualización del Código QR</h1>";
    echo "<p>Este código QR lleva al formulario principal.</p>";
    echo "<img src='$qr_filename' alt='Código QR'><br>";
    echo "<a href='index.html'>Cancelar</a> | ";
    echo "<form action='registro.php' method='POST'>";
    echo "<input type='hidden' name='confirm' value='yes'>";
    echo "<input type='hidden' name='qr_filename' value='$qr_filename'>";
    echo "<button type='submit'>Guardar QR</button>";
    echo "</form>";
    exit;
}

// Guardar el QR en la base de datos si se confirma
if (isset($_POST['confirm']) && $_POST['confirm'] == 'yes') {
    $qr_filename = $_POST['qr_filename'];

    // Insertar en la base de datos (sin otros datos para este caso)
    $stmt = $conn->prepare("INSERT INTO registros (qr_url) VALUES (?)");
    $stmt->bind_param("s", $qr_filename);

    if ($stmt->execute()) {
        echo "<h1>QR Guardado Exitosamente</h1>";
        echo "<p>El código QR ha sido almacenado en la base de datos.</p>";
        echo "<img src='$qr_filename' alt='Código QR'><br>";
        echo "<a href='index.html'>Volver al inicio</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
