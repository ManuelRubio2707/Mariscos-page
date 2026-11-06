<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.html');
    exit;
}
$es_admin = ($_SESSION['rol'] === 'administrador');
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mariscos Don Pancho - Sistema de Cobro</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1a237e, #4527a0);
            min-height: 100vh;
            padding: 20px;
        }

        .contenedor-principal {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        /* Columna Izquierda - Ticket */
        .columna-ticket {
            flex: 1 1 400px;
            background: #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            color: #000;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #666;
        }

        .ticket-header h1 {
            font-size: 22px;
            text-transform: uppercase;
        }

        .ticket-fecha {
            margin: 10px 0;
            font-size: 14px;
        }

        .ticket-tabla {
            width: 100%;
            margin: 15px 0;
        }

        .ticket-tabla th {
            text-align: left;
            padding: 5px 0;
            border-bottom: 1px solid #333;
        }

        .ticket-tabla td {
            padding: 6px 0;
        }

        .ticket-totales {
            margin-top: 20px;
        }

        .ticket-totales p {
            margin: 8px 0;
            font-size: 16px;
        }

        .ticket-totales .total-final {
            font-size: 20px;
            font-weight: bold;
            margin-top: 10px;
        }

        .ticket-pie {
            margin-top: 25px;
            text-align: center;
            font-weight: bold;
        }

        /* Columna Derecha - Menú */
        .columna-menu {
            flex: 1 1 500px;
            background: #fff;
            border-radius: 20px;
            padding: 25px;
        }

        .menu-titulo {
            text-align: center;
            color: #283593;
            font-size: 26px;
            margin-bottom: 25px;
        }

        .platillos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .platillo-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .platillo-nombre {
            text-align: center;
            font-size: 15px;
            color: #333;
        }

        .btn-suma {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #000;
            color: #fff;
            border: none;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-quitar-platillo {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e74c3c;
            color: #fff;
            border: none;
            font-size: 14px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modo-admin .btn-quitar-platillo {
            display: flex;
        }

        .categorias {
            display: flex;
            gap: 15px;
            margin: 20px 0;
        }

        .cat-btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: #90a4c7;
            color: #000;
            font-size: 16px;
            cursor: pointer;
        }

        .botones-accion {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .btn-accion {
            flex: 1;
            min-width: 140px;
            padding: 12px 10px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            color: #fff;
        }

        .btn-eliminar {
            background: linear-gradient(90deg, #283593, #5c6bc0);
        }

        .btn-pago {
            background: linear-gradient(90deg, #283593, #5c6bc0);
        }

        .btn-cancelar {
            background: #2c3e50;
        }

        /* Barra inferior */
        .barra-inferior {
            max-width: 1200px;
            margin: 25px auto 0;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-inferior {
            flex: 1;
            min-width: 180px;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: #e8eaf6;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-guardar {
            width: 100%;
            margin-top: 15px;
            padding: 12px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        /* Panel de Configuración */
        .panel-config {
            position: fixed; 
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.3);
            z-index: 9999;
            width: 90%;
            max-width: 450px;
            display: none;
            cursor: move;
            user-select: none;
        }

        .panel-config.mostrar {
            display: block;
        }

        .panel-config h3 {
            text-align: center;
            color: #283593;
            margin-bottom: 20px;
        }

    
       
       .panel-config h3 {
    position: relative;
    z-index: 11;
    margin-top: 36px; 
    text-align: center;
    color: #283593;
    margin-bottom: 20px;
}

        .panel-config input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
        }

        .panel-botones {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-agregar-platillo {
            flex: 1;
            padding: 12px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-cerrar-config {
            flex: 1;
            padding: 12px;
            background: #95a5a6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }

        .superposicion {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 998;
            display: none !important; 
            pointer-events: none;
        }

        .superposicion.mostrar {
            display: block;
        }

                .btn-modificar-platillo {
            position: absolute;
            top: -5px;
            left: -5px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #3498db;
            color: #fff;
            border: none;
            font-size: 12px;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .modo-admin .btn-modificar-platillo {
            display: flex;
        }

    </style>
</head>
<body>
<?php if ($es_admin): ?>
<a href="inventariado.php" style="
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #ff9800;
    color: #fff;
    padding: 14px 25px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    margin: 10px 0;
">
    📦 Inventariado
</a>
<?php endif; ?>

    <div class="contenedor-principal">
        <!-- Columna Izquierda: Ticket / Factura -->
        <div class="columna-ticket">
            <div class="ticket-header">
                <h1>Mariscos Don Chuyita</h1>
                <p class="ticket-fecha" id="fecha"></p>
            </div>

            <table class="ticket-tabla">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th style="text-align:right;">Importe</th>
                    </tr>
                </thead>
                <tbody id="cuerpo-factura">
                </tbody>
            </table>

            <div class="ticket-totales">
                <p class="total-final">Total: $<span id="total">0.00</span></p>
            </div>

            <button class="btn-guardar" onclick="guardarFactura()">Guardar Factura</button>
            <button class="btn-guardar" style="background:#e74c3c;" onclick="limpiarFactura()">Nueva Cuenta</button>

            <div class="ticket-pie">
                <p>¡Gracias por su visita!</p>
            </div>
        </div>

        <div class="columna-menu" id="contenedor-menu" <?php if ($es_admin) echo 'class="modo-admin"'; ?>>

            <h2 class="menu-titulo">Mariscos Don Chuyita</h2>

            <div class="platillos-grid" id="lista-platillos"></div>

            <div class="categorias">
                <button class="cat-btn" onclick="cambiarCategoria('comidas')" id="btn-comidas">🍽️ Comidas</button>
                <button class="cat-btn" onclick="cambiarCategoria('bebidas')" id="btn-bebidas">🥤 Bebidas</button>
            </div>

            <div class="botones-accion">
                <button class="btn-accion btn-eliminar" onclick="eliminarUltimo()">Eliminar</button>
                <button class="btn-accion btn-pago" onclick="abrirPago()">Tipo pago</button>
                <button class="btn-accion btn-cancelar" onclick="limpiarFactura()">Cancelaciones</button>
            </div>
        </div>
    </div>

    <!-- Barra inferior -->
    <div class="barra-inferior">
        <?php if ($es_admin): ?>
<button class="btn-inferior" onclick="abrirConfiguracion()">⚙️ Configuración del menú</button>
<?php endif; ?>
<button onclick="imprimirTicket()">Impresión</button>


    </div>

    <!-- Panel de Configuración -->
    <div class="panel-config" id="panelConfig">
        <h3>Agregar Platillo</h3>
        <input type="text" id="nombre-platillo" placeholder="Nombre del platillo" autocomplete="off">
        <input type="number" id="precio-platillo" placeholder="Precio" step="0.01" min="0">
        <div class="panel-botones">
            <button class="btn-agregar-platillo" onclick="agregarPlatilloAlMenu()">✅ Agregar</button>
            <button class="btn-cerrar-config" onclick="cerrarConfiguracion()">Cerrar</button>
        </div>
    </div>

    <script>
        let total = 0;
        let detalles = [];

       // Platillos organizados por categoría
    let menuPorCategoria = {
    comidas: [
        { nombre: 'Filete empanizado', precio: 160.00 },
        { nombre: 'Guiso al mojo de ajo', precio: 185.00 },
        { nombre: 'Camarón cucaracha', precio: 210.00 },
        { nombre: 'Chicharrón de pargo', precio: 195.00 }
    ],
    bebidas: [
        { nombre: 'Coca-Cola', precio: 25.00 },
        { nombre: 'Limonada', precio: 30.00 },
        { nombre: 'Cerveza', precio: 35.00 }
    ]
};

// Categoría activa
let categoriaActual = 'comidas';
let platillos = [...menuPorCategoria.comidas];

        // Dibujar menú automáticamente
        function dibujarMenu() {
            const contenedor = document.getElementById('lista-platillos');
            contenedor.innerHTML = '';
            platillos.forEach((p, i) => {
                const tarjeta = document.createElement('div');
                tarjeta.className = 'platillo-card';
               tarjeta.innerHTML = `
    <?php if ($es_admin): ?>
    <button class="btn-quitar-platillo" onclick="quitarPlatillo(${i})">×</button>
    <button class="btn-modificar-platillo" onclick="modificarPlatillo('${p.nombre}')">✏️</button>
    <?php endif; ?>
    <span class="platillo-nombre">${p.nombre}<br>$${p.precio.toFixed(2)}</span>
    <button class="btn-suma" onclick="agregarAlTicket('${p.nombre}', ${p.precio})">+</button>
`;

                contenedor.appendChild(tarjeta);
            });
        }

        // 💾 Guardar y cargar por categoría
function guardarPlatillos() {
    localStorage.setItem(`platillos_${categoriaActual}`, JSON.stringify(platillos));
}

function cargarPlatillos() {
    const guardados = localStorage.getItem(`platillos_${categoriaActual}`);
    if (guardados) {
        platillos = JSON.parse(guardados);
    }
}


        // Mostrar fecha y cargar menú
       document.getElementById('fecha').textContent = new Date().toLocaleString('es-MX');
       cambiarCategoria('comidas'); // ✅ Carga comidas + lo guardado


        function agregarAlTicket(nombre, precio) {
            const cuerpo = document.getElementById('cuerpo-factura');
            const fila = document.createElement('tr');
            fila.innerHTML = `<td>${nombre}</td><td style="text-align:right;">$${precio.toFixed(2)}</td>`;
            cuerpo.appendChild(fila);
            total += precio;
            detalles.push({ nombre, precio });
            document.getElementById('total').textContent = total.toFixed(2);
        }

        function eliminarUltimo() {
            if (detalles.length === 0) {
                alert('⚠️ No hay platillos para eliminar');
                return;
            }
            const ultimo = detalles.pop();
            total -= ultimo.precio;
            const cuerpo = document.getElementById('cuerpo-factura');
            cuerpo.removeChild(cuerpo.lastChild);
            document.getElementById('total').textContent = total.toFixed(2);
        }

        function limpiarFactura() {
            document.getElementById('cuerpo-factura').innerHTML = '';
            total = 0;
            detalles = [];
            document.getElementById('total').textContent = '0.00';
            document.getElementById('fecha').textContent = new Date().toLocaleString('es-MX');
            cambiarCategoria('comidas'); // ✅ Inicia con comidas y carga lo guardado

        }

        function guardarFactura() {
    if (total === 0) {
        alert('⚠️ Agrega platillos antes de guardar');
        return;
    }
    if (!tipoPagoSeleccionado) {
        alert('⚠️ Selecciona el tipo de pago');
        return;
    }
    fetch('guardar.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'total=' + total +
              '&detalles=' + encodeURIComponent(JSON.stringify(detalles)) +
              '&tipo_pago=' + encodeURIComponent(tipoPagoSeleccionado) // ✅ Envía tipo de pago
    })
    .then(res => res.text())
    .then(msj => {
        alert(msj);
       tipoPagoSeleccionado = 'Físico'; // ✅ Tilde incluida

        limpiarFactura();
    })
    .catch(err => alert('❌ Error: ' + err));
}


        // Funciones del panel de configuración
        function abrirConfiguracion() {
            document.getElementById('panelConfig').classList.add('mostrar');
            document.getElementById('contenedor-menu').classList.add('modo-admin');
        }

      function cerrarConfiguracion() {
    document.getElementById('panelConfig').classList.remove('mostrar');
    document.getElementById('contenedor-menu').classList.remove('modo-admin');
    document.getElementById('nombre-platillo').value = '';
    document.getElementById('precio-platillo').value = '';
}
       function agregarPlatilloAlMenu() {
    const nombre = document.getElementById('nombre-platillo').value.trim();
    const precio = parseFloat(document.getElementById('precio-platillo').value);
    
    if (!nombre || isNaN(precio) || precio <= 0) {
        alert('⚠️ Escribe nombre y precio válidos');
        return;
    }

    // Buscar si ya existe
    const existe = platillos.find(p => p.nombre === nombre);
    if (existe) {
        // Modificar existente
        existe.precio = precio;
        alert(`✅ Platillo "${nombre}" ya existía → se modificó el precio`);
    } else {
        // Agregar nuevo
        platillos.push({ nombre, precio });
        alert(`✅ Platillo "${nombre}" agregado`);
    }

    dibujarMenu();
    guardarPlatillos();
    document.getElementById('nombre-platillo').value = '';
    document.getElementById('precio-platillo').value = '';
}

       function quitarPlatillo(indice) {
    if (confirm('¿Eliminar este platillo del menú?')) {
        platillos.splice(indice, 1);
        dibujarMenu();
        guardarPlatillos();
    }
}

        function modificarPlatillo(nombreOriginal) {
    const nuevoNombre = prompt("Nuevo nombre del platillo:", nombreOriginal);
    if (nuevoNombre === null) return;
    
    const indice = platillos.findIndex(p => p.nombre === nombreOriginal);
    if (indice === -1) {
        alert("⚠️ Platillo no existente");
        return;
    }
    
    const nuevoPrecio = prompt("Nuevo precio:", platillos[indice].precio);
    if (nuevoPrecio === null) return;
    
    platillos[indice].nombre = nuevoNombre.trim() || nombreOriginal;
    platillos[indice].precio = parseFloat(nuevoPrecio) || platillos[indice].precio;
    dibujarMenu();
    guardarPlatillos();
}



// Mover ventanita — arrastre SOLO por la barra superior
let arrastrar = false;
let offsetX = 0;
let offsetY = 0;
const panelMovil = document.getElementById('panelConfig');

// Crear la barra de arrastre
const barraArrastre = document.createElement('div');
barraArrastre.style.cssText = `
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 32px;
    background: #283593;
    border-radius: 15px 15px 0 0;
    cursor: move;
    z-index: 10;
`;
panelMovil.style.position = 'fixed';
panelMovil.insertBefore(barraArrastre, panelMovil.firstChild);

// Solo arrastrar si tocas la barra
barraArrastre.addEventListener('mousedown', function(e) {
    arrastrar = true;
    const rect = panelMovil.getBoundingClientRect();
    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;
    panelMovil.style.transform = 'none';
    panelMovil.style.left = rect.left + 'px';
    panelMovil.style.top = rect.top + 'px';
});

document.addEventListener('mousemove', function(e) {
    if (!arrastrar) return;
    panelMovil.style.left = (e.clientX - offsetX) + 'px';
    panelMovil.style.top = (e.clientY - offsetY) + 'px';
});

document.addEventListener('mouseup', function() {
    arrastrar = false;
});

// ✅ También funciona con pantalla táctil si la usas
panelMovil.addEventListener('touchstart', function(e) {
    arrastrar = true;
    const rect = panelMovil.getBoundingClientRect();
    const toque = e.touches[0];
    offsetX = toque.clientX - rect.left;
    offsetY = toque.clientY - rect.top;
    panelMovil.style.transform = 'none';
    panelMovil.style.left = rect.left + 'px';
    panelMovil.style.top = rect.top + 'px';
});

document.addEventListener('touchmove', function(e) {
    if (!arrastrar) return;
    const toque = e.touches[0];
    panelMovil.style.left = (toque.clientX - offsetX) + 'px';
    panelMovil.style.top = (toque.clientY - offsetY) + 'px';
});

document.addEventListener('touchend', function() {
    arrastrar = false;
});

let tipoPagoSeleccionado = 'Físico';

function abrirPago() {
    document.getElementById('panelPago').style.display = 'block';
    // Centrar el panel igual que el de agregar
    const panel = document.getElementById('panelPago');
    panel.style.top = '50%';
    panel.style.left = '50%';
    panel.style.transform = 'translate(-50%, -50%)';
}

function cerrarPago() {
    document.getElementById('panelPago').style.display = 'none';
}

function seleccionarPago(tipo) {
    tipoPagoSeleccionado = tipo;
    alert(`✅ Pago seleccionado: ${tipo}`);
    cerrarPago();
}

// Cambiar entre Comidas y Bebidas
function cambiarCategoria(categoria) {
    categoriaActual = categoria;
    platillos = [...menuPorCategoria[categoria]];
    
    // Cargar platillos guardados de esa categoría
    cargarPlatillos(); // ✅ Llama a la función que ya tienes
    
    dibujarMenu();
    
    // Resaltar el botón activo
    document.querySelectorAll('.cat-btn').forEach(btn => {
        btn.style.background = '#90a4c7';
    });
    const btnActivo = document.getElementById(`btn-${categoria}`);
    if (btnActivo) {
        btnActivo.style.background = '#283593';
        btnActivo.style.color = '#fff';
    }
}

// 🖨️ Generar e imprimir factura
function imprimirTicket() {
    if (total === 0) {
        alert('⚠️ Agrega platillos antes de imprimir');
        return;
    }

    const fechaActual = new Date().toLocaleString('es-MX');
    let contenido = `
        <html>
        <head>
            <title>Factura - Mariscos Don Chuyita</title>
            <style>
                * { margin: 0; padding: 0; font-family: Arial, sans-serif; }
                body { padding: 20px; font-size: 14px; }
                .centro { text-align: center; margin-bottom: 10px; }
                .titulo { font-size: 20px; font-weight: bold; }
                .linea { border-bottom: 1px dashed #000; margin: 8px 0; }
                .fila { display: flex; justify-content: space-between; padding: 3px 0; }
                .total { font-weight: bold; font-size: 16px; margin-top: 8px; }
                .pie { margin-top: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="centro titulo">MARISCOS DON CHUYITA</div>
            <div class="centro">Ticket de Venta</div>
            <div class="centro">${fechaActual}</div>
            <div class="linea"></div>
            
            <div class="fila" style="font-weight:bold;">
                <span>Descripción</span>
                <span>Importe</span>
            </div>
            <div class="linea"></div>
    `;

    detalles.forEach(item => {
        contenido += `
            <div class="fila">
                <span>${item.nombre}</span>
                <span>$${item.precio.toFixed(2)}</span>
            </div>
        `;
    });

    contenido += `
            <div class="linea"></div>
            <div class="fila total">
                <span>TOTAL</span>
                <span>$${total.toFixed(2)}</span>
            </div>
            <div class="fila">
                <span>Forma de pago:</span>
                <span>${tipoPagoSeleccionado || 'No especificada'}</span>
            </div>
            <div class="pie">
                ¡Gracias por su visita!<br>
                - Mariscos Don Chuyita -
            </div>
        </body>
        </html>
    `;

    const ventana = window.open('', '_blank');
    ventana.document.write(contenido);
    ventana.document.close();
    ventana.print();
}




    </script>

<!-- Panel Tipo de Pago -->
<div class="panel-config" id="panelPago" style="display:none;">
    <h3>Forma de Pago</h3>
    <div style="display:flex; gap:15px; margin:20px 0;">
        <button class="btn-agregar-platillo" style="flex:1; padding:15px;" onclick="seleccionarPago('Físico')">
            💵 Efectivo / Físico
        </button>
        <button class="btn-agregar-platillo" style="flex:1; padding:15px; background:#3498db;" onclick="seleccionarPago('Transferencia')">
            🏦 Transferencia
        </button>
    </div>
    <div class="panel-botones">
        <button class="btn-cerrar-config" style="flex:1;" onclick="cerrarPago()">Cerrar</button>
    </div>
</div>

</body>
</html>