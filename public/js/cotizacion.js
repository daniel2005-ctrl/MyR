document.addEventListener("DOMContentLoaded", function () {
    const btnImprimirPDF = document.getElementById("btnImprimirPDF");

    const subsidioCheckbox = document.getElementById('subsidio');
    const infoSubsidio = document.getElementById('infoSubsidio');
    const infoCredito = document.getElementById('infoCredito');

    const switchParqueadero = document.getElementById('switchParqueadero');
    const parqueaderoCard = document.getElementById('parqueaderoCard');

    const viviendaSelect = document.getElementById('vivienda');
    const valorInput = document.getElementById('valor');
    const cuotaInicialInput = document.getElementById('cuota-inicial');
    const separacionInput = document.getElementById('separacion');

    const valorParqueaderoInput = document.getElementById('valorParqueadero');
    const cuotaInicialParqInput = document.getElementById('cuotaInicialParqueadero');
    const separacionParqInput = document.getElementById('separacionParqueadero');
    const plazoParqSelect = document.getElementById('plazoMeses');
    const cuotaMensualParqInput = document.getElementById('cuotaMensualParqueadero');

    const tieneAhorrosCheckbox = document.getElementById('tiene-ahorros');
    const campoAhorros = document.getElementById('campo-ahorros');
    const inputAhorros = document.getElementById('total-ahorros'); // Asegúrate de que este id coincida

    const plazoGeneral = document.getElementById('meses-modificable');
    const cuotaMensualGeneral = document.getElementById('cuota-mensual');

    let valorVivienda = 0;
    let valorParqueadero = 15000000;

    // Cambio de color del título
    const titulo = document.getElementById('titulo');
    if (titulo) {
        titulo.style.color = '#ff8000';  // Cambio de color del título a #ff8000
    }

    // Manejo del subsidio
    subsidioCheckbox.addEventListener('change', () => {
        infoSubsidio.style.display = subsidioCheckbox.checked ? 'block' : 'none';
        infoCredito.style.display = subsidioCheckbox.checked ? 'block' : 'none';
    });

    // Manejo del parqueadero
    switchParqueadero.addEventListener('change', () => {
        parqueaderoCard.style.display = switchParqueadero.checked ? 'block' : 'none';
        if (switchParqueadero.checked) calcularParqueadero();
    });

    // Manejo de ahorros
    tieneAhorrosCheckbox.addEventListener('change', () => {
        campoAhorros.style.display = tieneAhorrosCheckbox.checked ? 'block' : 'none';
        calcularCuotaMensual(); // Recálcular la cuota mensual cuando cambie la opción de ahorros
    });

    // Cambio de vivienda
    viviendaSelect.addEventListener('change', () => {
        const selectedOption = viviendaSelect.options[viviendaSelect.selectedIndex];
        valorVivienda = parseInt(selectedOption.getAttribute('data-precio')) || 0;

        valorInput.value = formatCurrency(valorVivienda);

        const cuotaInicial = valorVivienda * 0.30;
        cuotaInicialInput.value = formatCurrency(cuotaInicial);

        const separacion = cuotaInicial * 0.10;
        separacionInput.value = formatCurrency(separacion);

        calcularCuotaMensual();
    });

    // Cambio de plazo para parqueadero
    plazoParqSelect.addEventListener('change', calcularParqueadero);
    plazoGeneral.addEventListener('change', calcularCuotaMensual);

    // Cálculo de la cuota mensual
    inputAhorros.addEventListener('input', calcularCuotaMensual); // Asegurarse de que se actualice la cuota mensual al escribir en el campo de ahorros

    function calcularCuotaMensual() {
        const cuotaInicialBase = valorVivienda * 0.30;
        const separacion = cuotaInicialBase * 0.10;
        const plazo = parseInt(plazoGeneral.value) || 1;
        
        let totalAhorros = 0;
        if (tieneAhorrosCheckbox.checked) {
            totalAhorros = parseInt(inputAhorros.value) || 0;  // Si los ahorros están activos, obtener su valor
        }

        let cuotaMensual;
        if (tieneAhorrosCheckbox.checked) {
            // Fórmula con ahorros activos
            cuotaMensual = (cuotaInicialBase - totalAhorros + separacion) / plazo;
        } else {
            // Fórmula sin ahorros
            cuotaMensual = (cuotaInicialBase + separacion) / plazo;
        }

        cuotaMensualGeneral.value = formatCurrency(cuotaMensual);
    }

    // Cálculo de la cuota del parqueadero
    function calcularParqueadero() {
        valorParqueaderoInput.value = formatCurrency(valorParqueadero);
        const cuotaInicial = valorParqueadero * 0.30;
        cuotaInicialParqInput.value = formatCurrency(cuotaInicial);
        const separacion = cuotaInicial * 0.10;
        separacionParqInput.value = formatCurrency(separacion);
        const plazo = parseInt(plazoParqSelect.value) || 1;
        const cuotaMensual = (cuotaInicial * 0.90) / plazo;
        cuotaMensualParqInput.value = formatCurrency(cuotaMensual);
    }

    // Formateo de la moneda
    function formatCurrency(valor) {
        return valor.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
    }

    // Manejo de la creación del PDF
    btnImprimirPDF.addEventListener("click", async function () {
        // Cambiar color del botón a blanco cuando se hace clic
        btnImprimirPDF.style.backgroundColor = "#ffffff";
        btnImprimirPDF.style.color = "#ff8000";

        // Deshabilitar el botón para evitar clics múltiples
        btnImprimirPDF.disabled = true;

        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        let startX = 20;
        let startY = 20;
        const rowHeight = 10;
        const columnWidth = [80, 80];

        function dibujarTabla(titulo, datos) {
            doc.setFontSize(14);
            doc.setTextColor(0, 0, 0);
            doc.text(titulo, startX, startY);
            startY += 5;

            doc.setFontSize(12);
            doc.setFillColor(255, 128, 0);
            doc.setTextColor(255, 255, 255);
            datos[0].forEach((text, i) => {
                doc.rect(startX + i * columnWidth[i], startY, columnWidth[i], rowHeight, 'FD');
                doc.text(text, startX + i * columnWidth[i] + 5, startY + 7);
            });

            doc.setTextColor(0, 0, 0);
            datos.slice(1).forEach((fila, idx) => {
                fila.forEach((cell, i) => {
                    doc.rect(startX + i * columnWidth[i], startY + rowHeight * (idx + 1), columnWidth[i], rowHeight);
                    doc.text(cell, startX + i * columnWidth[i] + 5, startY + rowHeight * (idx + 1) + 7);
                });
            });

            startY += rowHeight * (datos.length);
            startY += 10;
        }

        // 🏢 Encabezado con nombre de la constructora, fecha y hora
        // Encabezado con nombre y logo
        doc.setFontSize(18);
        doc.setTextColor(255, 128, 0);
        doc.text("MYR Proyectos y Construcciones", 20, startY);

        // Agrega el logo a la derecha del nombre
        try {
            const imageBase64 = await toBase64('/imagenes/logos.png'); // Ruta del logo
            doc.addImage(imageBase64, 'PNG', 130, startY - 6, 50, 25); // Ajusta posición y tamaño
        } catch (error) {
            console.error("Error cargando imagen del encabezado:", error);
        }

        startY += 15;

        // Fecha y hora debajo
        const fechaHora = new Date().toLocaleString('es-CO', { hour12: false });
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text(`Fecha y Hora: ${fechaHora}`, 20, startY);
        startY += 15;

        // Línea de separación
        doc.setDrawColor(255, 128, 0);
        doc.line(20, startY, 190, startY);
        startY += 5;

        const proyectoSeleccionado = viviendaSelect.options[viviendaSelect.selectedIndex]?.text || "No seleccionado";

        // 🏠 Tabla de Vivienda
        dibujarTabla('Detalles de la Vivienda', [
            ['Descripción', 'Valor'],
            [`Proyecto: ${proyectoSeleccionado}`, valorInput.value],
            ['Cuota Inicial', cuotaInicialInput.value],
            ['Separación', separacionInput.value]
        ]);

        // 🚗 Tabla de Parqueadero (con plazo y cuota)
        if (switchParqueadero.checked) {
            dibujarTabla('Detalles del Parqueadero', [
                ['Descripción', 'Valor'],
                ['Valor Parqueadero', valorParqueaderoInput.value],
                ['Cuota Inicial Parqueadero', cuotaInicialParqInput.value],
                ['Separación Parqueadero', separacionParqInput.value],
                [`Plazo: ${plazoParqSelect.value} meses`, `Cuota Mensual: ${cuotaMensualParqInput.value}`]
            ]);
        }

        // 💰 Ahorros
        const tieneAhorros = tieneAhorrosCheckbox.checked ? "Sí" : "No";
        const valorAhorros = inputAhorros.value || "0";  // Captura el valor correctamente

        dibujarTabla('Información sobre Ahorros', [
            ['¿Tiene ahorros?', 'Valor de los ahorros'],
            [tieneAhorros, formatCurrency(parseInt(valorAhorros) || 0)]  // Muestra el valor correctamente
        ]);

        // 💳 Financiamiento General
        dibujarTabla('Resumen de Financiamiento', [
            ['Plazo', 'Cuota Mensual'],
            [`${plazoGeneral.value} meses`, cuotaMensualGeneral.value]
        ]);

        // Generar el PDF
        doc.save("cotización_proyecto.pdf");

        // Restaurar el estado original del botón después de 1 segundo
        setTimeout(() => {
            btnImprimirPDF.style.backgroundColor = "#ff8000"; // Regresa al color original
            btnImprimirPDF.style.color = "white"; // Regresa al color de texto original
            btnImprimirPDF.disabled = false; // Habilitar el botón nuevamente
        }, 1000); // Esto se ejecuta después de 1 segundo, suficiente para que el PDF se descargue
    });

    // Función para convertir imagen a Base64
    function toBase64(url) {
        return fetch(url)
            .then(response => response.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }
});
