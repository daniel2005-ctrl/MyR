document.addEventListener("DOMContentLoaded", function () {
    // Validación de acceso
    if (!localStorage.getItem("usuario")) {
        document.getElementById("contenido").style.display = "none";
        Swal.fire({
            icon: 'warning',
            title: 'Acceso restringido',
            text: 'Debes iniciar sesión para acceder a esta sección.',
            confirmButtonText: 'Volver a la página de inicio'
        }).then(() => {
            window.location.href = "/login";
        });
    }

    // Mostrar secciones de subsidio o crédito
    document.getElementById("subsidio").addEventListener("change", function () {
        const visible = this.checked;
        document.getElementById("infoSubsidio").style.display = visible ? "block" : "none";
        document.getElementById("infoCredito").style.display = visible ? "block" : "none";
    });

    // Eventos de vivienda
    document.getElementById("vivienda").addEventListener("change", calcularCuotas);
    document.getElementById("meses-modificable").addEventListener("change", calcularCuotaMensual);
    document.getElementById("total-ahorros").addEventListener("input", calcularCuotaMensual);

    // Parqueadero
    const switchParqueadero = document.getElementById('switchParqueadero');
    const parqueaderoCard = document.getElementById('parqueaderoCard');
    const plazoMeses = document.getElementById('plazoMeses');

    switchParqueadero.addEventListener('change', function () {
        if (this.checked) {
            parqueaderoCard.style.display = 'block';
            calcularValoresParqueadero();
        } else {
            parqueaderoCard.style.display = 'none';
        }
    });

    if (plazoMeses) {
        plazoMeses.addEventListener('change', function () {
            calcularCuotaMensualParqueadero();
        });
    }
});

// ---------------------------------------------------
// FUNCIONES DE VIVIENDA
// ---------------------------------------------------

function formatearNumero(numero) {
    return Math.round(numero).toLocaleString("es-CO");
}

function calcularCuotas() {
    const viviendaSelect = document.getElementById("vivienda");
    const selectedOption = viviendaSelect.options[viviendaSelect.selectedIndex];

    if (!selectedOption || !selectedOption.hasAttribute("data-precio")) return;

    const precio = parseFloat(selectedOption.getAttribute("data-precio"));
    const cuotaInicial = precio * 0.30;
    const separacion = cuotaInicial * 0.10;

    document.getElementById("valor").value = formatearNumero(precio);
    document.getElementById("cuota-inicial").value = formatearNumero(cuotaInicial);
    document.getElementById("separacion").value = formatearNumero(separacion);

    calcularCuotaMensual();
}

function mostrarCampoAhorros() {
    const campo = document.getElementById("campo-ahorros");
    campo.style.display = document.getElementById("tiene-ahorros").checked ? "block" : "none";
    calcularCuotaMensual();
}

function calcularCuotaMensual() {
    const cuotaInicial = parseFloat(document.getElementById("cuota-inicial").value.replace(/\./g, "")) || 0;
    const separacion = parseFloat(document.getElementById("separacion").value.replace(/\./g, "")) || 0;
    const meses = parseInt(document.getElementById("meses-modificable").value);
    const ahorros = document.getElementById("tiene-ahorros").checked ? parseFloat(document.getElementById("total-ahorros").value) || 0 : 0;

    const saldoPendiente = cuotaInicial - (separacion + ahorros);
    const cuotaMensual = saldoPendiente > 0 ? saldoPendiente / meses : 0;

    document.getElementById("cuota-mensual").value = saldoPendiente > 0 ? formatearNumero(cuotaMensual) : "";
}

// ---------------------------------------------------
// FUNCIONES DE PARQUEADERO
// ---------------------------------------------------

function calcularValoresParqueadero() {
    const smmlv = 1160000;
    const valorTotal = 15 * smmlv;
    const cuotaInicial = valorTotal * 0.30;
    const separacion = cuotaInicial * 0.10;

    document.getElementById("valorParqueadero").value = formatCurrency(valorTotal);
    document.getElementById("cuotaInicialParqueadero").value = formatCurrency(cuotaInicial);
    document.getElementById("separacionParqueadero").value = formatCurrency(separacion);

    calcularCuotaMensualParqueadero(valorTotal, cuotaInicial);
}

function calcularCuotaMensualParqueadero(valorTotal = null, cuotaInicial = null) {
    const smmlv = 1160000;
    const total = valorTotal ?? 15 * smmlv;
    const inicial = cuotaInicial ?? total * 0.30;
    const meses = parseInt(document.getElementById("plazoMeses").value);
    const saldoAFinanciar = total - inicial;
    const cuotaMensual = saldoAFinanciar / meses;

    document.getElementById("cuotaMensualParqueadero").value = formatCurrency(cuotaMensual);
}

// Formato de moneda COP
function formatCurrency(value) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(value);
}
