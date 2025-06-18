document.addEventListener("DOMContentLoaded", function () {
    const btnImprimirPDF = document.getElementById("btnImprimirPDF");

    const subsidioCheckbox = document.getElementById('subsidio');
    const infoSubsidio = document.getElementById('infoSubsidio');
    const infoCredito = document.getElementById('infoCredito');

    const switchParqueadero = document.getElementById('switchParqueadero');
    const parqueaderoCard = document.getElementById('parqueaderoCard');

    const viviendaSelect = document.getElementById('vivienda');
    const tipoApartamentoSelect = document.getElementById('tipoApartamento');
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
    const inputAhorros = document.getElementById('total-ahorros');

    const plazoGeneral = document.getElementById('meses-modificable');
    const cuotaMensualGeneral = document.getElementById('cuota-mensual');

    let valorVivienda = 0;
    let valorParqueadero = 0;
    let currentProject = null;
    let currentApartmentType = null;

    // Get salary minimum from global variable
    const salarioMinimo = window.salarioMinimo || 1300000;

    // Subsidio/Credito toggle
    if (subsidioCheckbox) {
        subsidioCheckbox.addEventListener('change', () => {
            if (infoSubsidio) infoSubsidio.classList.toggle('d-none', !subsidioCheckbox.checked);
            if (infoCredito) infoCredito.classList.toggle('d-none', !subsidioCheckbox.checked);
        });
    }

    // Parqueadero toggle
    // En el event listener del switchParqueadero:
    if (switchParqueadero) {
        switchParqueadero.addEventListener('change', () => {
            if (parqueaderoCard) {
                if (switchParqueadero.checked) {
                    parqueaderoCard.style.display = 'block';
                    // Calcular valores automáticamente al mostrar la card
                    calcularParqueadero();
                } else {
                    parqueaderoCard.style.display = 'none';
                }
            }
        });
    }
    // Ahorros toggle
    if (tieneAhorrosCheckbox && campoAhorros) {
        tieneAhorrosCheckbox.addEventListener('change', () => {
            campoAhorros.style.display = tieneAhorrosCheckbox.checked ? 'block' : 'none';
            calcularCuotaMensual();
        });
    }

    // Project selection
    if (viviendaSelect) {
        viviendaSelect.addEventListener('change', () => {
            const selectedOption = viviendaSelect.options[viviendaSelect.selectedIndex];
            const tipoApartamentoContainer = document.getElementById('tipoApartamentoContainer');
            const parqueaderoContainer = document.getElementById('parqueaderoContainer');
            
            if (selectedOption.value) {
                const precioMin = parseInt(selectedOption.getAttribute('data-precio-min')) || 0;
                const precioMax = parseInt(selectedOption.getAttribute('data-precio-max')) || 0;
                
                // === DEBUG: AGREGAR ESTAS LÍNEAS ===
                console.log('=== DEBUG PARQUEADERO ===');
                console.log('Proyecto seleccionado:', selectedOption.getAttribute('data-nombre'));
                console.log('data-parqueadero (raw):', selectedOption.getAttribute('data-parqueadero'));
                console.log('Elemento parqueaderoContainer encontrado:', !!parqueaderoContainer);
                // === FIN DEBUG ===
                
                currentProject = {
                    id: selectedOption.value,
                    nombre: selectedOption.getAttribute('data-nombre'),
                    precioMin: precioMin,
                    precioMax: precioMax,
                    parqueadero: parseInt(selectedOption.getAttribute('data-parqueadero')) || 0
                };
                
                // === DEBUG: AGREGAR ESTAS LÍNEAS ===
                console.log('currentProject.parqueadero:', currentProject.parqueadero);
                // === FIN DEBUG ===
                
                // Verificar si el proyecto tiene precio de parqueadero
                const tieneParqueadero = currentProject.parqueadero > 0;
                
                // === DEBUG: AGREGAR ESTAS LÍNEAS ===
                console.log('tieneParqueadero:', tieneParqueadero);
                console.log('========================');
                // === FIN DEBUG ===
                
                // Mostrar/ocultar sección de parqueadero
                if (parqueaderoContainer) {
                    parqueaderoContainer.style.display = tieneParqueadero ? 'block' : 'none';
                    // === DEBUG: AGREGAR ESTA LÍNEA ===
                    console.log('parqueaderoContainer.style.display:', parqueaderoContainer.style.display);
                    // === FIN DEBUG ===
                }
                
                // Si no hay parqueadero disponible, desmarcar el checkbox y ocultar la card
                if (!tieneParqueadero && switchParqueadero) {
                    switchParqueadero.checked = false;
                    if (parqueaderoCard) {
                        parqueaderoCard.style.display = 'none';
                    }
                }
                
                // Verificar si el proyecto tiene ambos tipos de apartamento
                const tieneAmbosTipos = (precioMin > 0 && precioMax > 0 && precioMin !== precioMax);
                
                if (tieneAmbosTipos) {
                    // Mostrar selector de tipo de apartamento
                    if (tipoApartamentoContainer) {
                        tipoApartamentoContainer.style.display = 'block';
                    }
                    if (tipoApartamentoSelect) {
                        tipoApartamentoSelect.disabled = false;
                        tipoApartamentoSelect.value = '';
                    }
                    currentApartmentType = null;
                    clearViviendaInputs();
                } else {
                    // Ocultar selector y seleccionar automáticamente el tipo disponible
                    if (tipoApartamentoContainer) {
                        tipoApartamentoContainer.style.display = 'none';
                    }
                    
                    // Determinar qué tipo usar automáticamente
                    if (precioMin > 0) {
                        currentApartmentType = 'min';
                        if (tipoApartamentoSelect) {
                            tipoApartamentoSelect.value = 'min';
                        }
                    } else if (precioMax > 0) {
                        currentApartmentType = 'max';
                        if (tipoApartamentoSelect) {
                            tipoApartamentoSelect.value = 'max';
                        }
                    }
                    
                    // Calcular automáticamente
                    calcularVivienda();
                    if (switchParqueadero && switchParqueadero.checked) {
                        calcularParqueadero();
                    }
                    calcularCuotaMensual();
                }
            } else {
                currentProject = null;
                currentApartmentType = null;
                if (tipoApartamentoContainer) {
                    tipoApartamentoContainer.style.display = 'none';
                }
                if (parqueaderoContainer) {
                    parqueaderoContainer.style.display = 'none';
                }
                if (tipoApartamentoSelect) {
                    tipoApartamentoSelect.disabled = true;
                    tipoApartamentoSelect.value = '';
                }
                if (switchParqueadero) {
                    switchParqueadero.checked = false;
                    if (parqueaderoCard) {
                        parqueaderoCard.style.display = 'none';
                    }
                }
                clearViviendaInputs();
            }
        });
    }

    // Apartment type selection
    if (tipoApartamentoSelect) {
        tipoApartamentoSelect.addEventListener('change', () => {
            if (currentProject && tipoApartamentoSelect.value) {
                currentApartmentType = tipoApartamentoSelect.value;
                calcularVivienda();
                if (switchParqueadero && switchParqueadero.checked) {
                    calcularParqueadero();
                }
                calcularCuotaMensual();
            }
        });
    }

    // Plazo changes
    if (plazoParqSelect) {
        plazoParqSelect.addEventListener('change', () => {
            if (switchParqueadero && switchParqueadero.checked) {
                calcularParqueadero();
            }
        });
    }
    
    if (plazoGeneral) {
        plazoGeneral.addEventListener('change', calcularCuotaMensual);
    }
    
    if (inputAhorros) {
        inputAhorros.addEventListener('input', calcularCuotaMensual);
    }

    function calcularVivienda() {
        if (!currentProject || !currentApartmentType) return;
        
        const precioEnSalarios = currentApartmentType === 'min' ? currentProject.precioMin : currentProject.precioMax;
        valorVivienda = precioEnSalarios * salarioMinimo;
        
        if (valorInput) {
            valorInput.value = formatCurrency(valorVivienda);
        }
        
        const cuotaInicial = valorVivienda * 0.30;
        if (cuotaInicialInput) {
            cuotaInicialInput.value = formatCurrency(cuotaInicial);
        }
        
        const separacion = cuotaInicial * 0.10;
        if (separacionInput) {
            separacionInput.value = formatCurrency(separacion);
        }
    }

    function calcularParqueadero() {
        if (!currentProject || !currentProject.parqueadero || currentProject.parqueadero === 0) return;
        
        // Calcular valor del parqueadero en pesos (SMMLV * salario mínimo)
        valorParqueadero = currentProject.parqueadero * salarioMinimo;
        
        if (valorParqueaderoInput) {
            valorParqueaderoInput.value = formatCurrency(valorParqueadero);
        }
        
        const cuotaInicial = valorParqueadero * 0.30;
        if (cuotaInicialParqInput) {
            cuotaInicialParqInput.value = formatCurrency(cuotaInicial);
        }
        
        const separacion = cuotaInicial * 0.10;
        if (separacionParqInput) {
            separacionParqInput.value = formatCurrency(separacion);
        }
        
        const plazo = parseInt(plazoParqSelect?.value) || 36; // Default 36 meses
        const cuotaMensual = (cuotaInicial * 0.90) / plazo;
        if (cuotaMensualParqInput) {
            cuotaMensualParqInput.value = formatCurrency(cuotaMensual);
        }
    }

    function calcularCuotaMensual() {
        if (valorVivienda === 0) return;
        
        const cuotaInicialBase = valorVivienda * 0.30;
        const separacion = cuotaInicialBase * 0.10;
        const plazo = parseInt(plazoGeneral?.value) || 1;
        
        let totalAhorros = 0;
        if (tieneAhorrosCheckbox && tieneAhorrosCheckbox.checked) {
            totalAhorros = parseInt(inputAhorros?.value) || 0;
        }

        let cuotaMensual;
        if (tieneAhorrosCheckbox && tieneAhorrosCheckbox.checked) {
            cuotaMensual = (cuotaInicialBase - totalAhorros + separacion) / plazo;
        } else {
            cuotaMensual = (cuotaInicialBase + separacion) / plazo;
        }

        if (cuotaMensualGeneral) {
            cuotaMensualGeneral.value = formatCurrency(cuotaMensual);
        }
    }

    function clearViviendaInputs() {
        if (valorInput) valorInput.value = '';
        if (cuotaInicialInput) cuotaInicialInput.value = '';
        if (separacionInput) separacionInput.value = '';
        if (cuotaMensualGeneral) cuotaMensualGeneral.value = '';
        valorVivienda = 0;
        currentApartmentType = null;
    }

    function formatCurrency(valor) {
        return valor.toLocaleString('es-CO', { style: 'currency', currency: 'COP' });
    }

    // PDF Generation with dompdf instead of jsPDF
    if (btnImprimirPDF) {
        btnImprimirPDF.addEventListener("click", function () {
            // Verificar que hay datos para generar el PDF
            if (!currentProject || !currentProject.id) {
                alert('Por favor selecciona un proyecto antes de generar el PDF.');
                return;
            }
    
            const tipoApartamento = document.getElementById('tipoApartamento')?.value;
            if (!tipoApartamento) {
                alert('Por favor selecciona un tipo de apartamento antes de generar el PDF.');
                return;
            }
    
            // Deshabilitar el botón temporalmente
            btnImprimirPDF.disabled = true;
    
            // En lugar de document.getElementById('formPDF').submit();
            
            // Crear FormData con los datos del formulario
            const formData = new FormData();
            formData.append('nombre_proyecto', currentProject.nombre || '');
            formData.append('valor_vivienda', document.getElementById('valor').value || '0');
            formData.append('cuota_inicial', document.getElementById('cuota-inicial').value || '0');
            formData.append('separacion', document.getElementById('separacion').value || '0');
            formData.append('valor_parqueadero', document.getElementById('valorParqueadero').value || '0');
            formData.append('cuota_inicial_parq', document.getElementById('cuotaInicialParqueadero').value || '0');
            formData.append('separacion_parq', document.getElementById('separacionParqueadero').value || '0');
            formData.append('plazo_general', document.getElementById('meses-modificable').value || '36');
            formData.append('cuota_mensual_general', document.getElementById('cuota-mensual').value || '0');
            formData.append('total_ahorros', document.getElementById('total-ahorros')?.value || '0');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      

            
            // Enviar con fetch
            fetch('/generar-pdf', {
                method: 'POST',
                body: formData
            })
            .then(response => response.blob())
            .then(blob => {
                // Crear URL del blob y descargar
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'cotizacion_proyecto.pdf';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            })
            .catch(error => {
                console.error('Error al generar PDF:', error);
                alert('Error al generar el PDF. Por favor, inténtalo de nuevo.');
            })
            .finally(() => {
                // Restaurar el botón
                setTimeout(() => {
                    btnImprimirPDF.style.backgroundColor = "#ff8000";
                    btnImprimirPDF.style.color = "white";
                    btnImprimirPDF.disabled = false;
                }, 2000);
            });
    
            // Restaurar el botón después de un breve delay
            setTimeout(() => {
                btnImprimirPDF.style.backgroundColor = "#ff8000";
                btnImprimirPDF.style.color = "white";
                btnImprimirPDF.disabled = false;
            }, 2000);
        });
    }

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

    // Form submission functionality
    function submitCotizacion() {
        if (!currentProject || !currentApartmentType) {
            alert('Por favor selecciona un proyecto y tipo de apartamento');
            return;
        }
        
        const formData = {
            proyecto_id: currentProject.id,
            tipo_apto: currentApartmentType,
            incluye_parqueadero: switchParqueadero ? (switchParqueadero.checked ? 1 : 0) : 0
        };
        
        console.log('Datos a enviar:', formData);
        // Aquí puedes agregar la lógica para enviar al servidor
    }

   
});

// Actualizar la lógica para mostrar/ocultar el parqueadero:
const tieneParqueadero = currentProject.parqueadero > 0;
// En el event listener del proyecto, después de mostrar el parqueaderoContainer:
if (tieneParqueadero) {
    parqueaderoContainer.style.display = 'block';
    // Calcular automáticamente los valores del parqueadero
    calcularParqueadero();
} else {
    parqueaderoContainer.style.display = 'none';
    switchParqueadero.checked = false;
    if (parqueaderoCard) {
        parqueaderoCard.style.display = 'none';
    }
}