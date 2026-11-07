<?php
include 'config.php';

// Recibir datos
$total = $_POST['total'] ?? 0;
$detalles = json_decode(urldecode($_POST['detalles'] ?? '[]'), true);
$tipo_pago = $_POST['tipo_pago'] ?? 'Físico';

// Insertar pedido principal
$stmt = $conexion->prepare("INSERT INTO pedidos (total, fecha, tipo_pago) VALUES (?, NOW(), ?)");
$stmt->bind_param("ds", $total, $tipo_pago);

if ($stmt->execute()) {
    $id_pedido = $conexion->insert_id;
    $stmt->close();

    // Insertar cada detalle
    foreach ($detalles as $item) {
        $nombre = $item['nombre'];
        $precio = $item['precio'];

        $stmt_det = $conexion->prepare("INSERT INTO detalle_pedido (id_pedido, nombre_platillo, precio) VALUES (?, ?, ?)");
        $stmt_det->bind_param("isd", $id_pedido, $nombre, $precio);
        $stmt_det->execute();
        $stmt_det->close();
    }

    echo "✅ Factura guardada correctamente\nPedido Nº: $id_pedido\nTotal: $$total\nPago: $tipo_pago";
} else {
    echo "❌ Error al guardar: " . $conexion->error;
}

$conexion->close();
?>
