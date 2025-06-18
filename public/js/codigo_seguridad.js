// Variables globales
let currentSecurityCode = '';
let timerInterval;
let codeExpiresAt = null;

// Función para obtener el código actual del servidor
async function fetchCurrentCode() {
    try {
        const response = await fetch('/api/security-code/current', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                currentSecurityCode = data.code;
                codeExpiresAt = new Date(data.expires_at);
                // REMOVIDO: console.log('Código obtenido del servidor:', currentSecurityCode);
                // REMOVIDO: console.log('Expira en:', codeExpiresAt);
                return true;
            }
        }
    } catch (error) {
        console.error('Error obteniendo código del servidor');
    }
    return false;
}

// Función para generar un nuevo código en el servidor
async function generateNewCode() {
    try {
        const response = await fetch('/api/security-code/generate', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                currentSecurityCode = data.code;
                codeExpiresAt = new Date(data.expires_at);
                // REMOVIDO: console.log('Nuevo código generado:', currentSecurityCode);
                // REMOVIDO: console.log('Expira en:', codeExpiresAt);
                return true;
            }
        }
    } catch (error) {
        console.error('Error generando código');
    }
    return false;
}

// Función para verificar si el código actual sigue siendo válido
function isCodeValid() {
    if (!codeExpiresAt) return false;
    return new Date() < codeExpiresAt;
}

// Función para obtener el tiempo restante
function getRemainingTime() {
    if (!codeExpiresAt) return 0;
    const now = new Date();
    const remaining = (codeExpiresAt - now) / 1000;
    return Math.max(0, remaining);
}

// Función para inicializar el código
async function initializeCode() {
    // REMOVIDO: console.log('Inicializando código de seguridad...');
    await fetchCurrentCode();
    return currentSecurityCode;
}

// Función para actualizar la visualización del código
function updateSecurityCodeDisplay() {
    const codeElement = document.getElementById('security-code');
    if (codeElement && currentSecurityCode) {
        codeElement.textContent = currentSecurityCode;
        // REMOVIDO: console.log('Display actualizado con código:', currentSecurityCode);
    }
}

// Función para sincronizar el timer
function syncTimer() {
    const timerElement = document.getElementById('timer');
    if (timerElement) {
        const remaining = getRemainingTime();
        const minutes = Math.floor(remaining / 60);
        const seconds = Math.floor(remaining % 60);
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}

// Función para iniciar el timer
function startTimer() {
    const timerElement = document.getElementById('timer');
    
    if (timerElement) {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
        
        syncTimer();
        
        timerInterval = setInterval(async () => {
            const remaining = getRemainingTime();
            
            if (remaining <= 0) {
                // REMOVIDO: console.log('Código expirado, generando nuevo...');
                await generateNewCode();
                updateSecurityCodeDisplay();
                syncTimer();
            } else {
                syncTimer();
            }
        }, 1000);
    }
}

// Función para validar el código de seguridad
async function validateSecurityCode(inputCode) {
    try {
        const response = await fetch('/api/security-code/validate', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ code: inputCode })
        });
        
        if (response.ok) {
            const data = await response.json();
            // REMOVIDO: console.log('Validación del código:', inputCode, '- Resultado:', data.valid);
            return data.valid;
        }
    } catch (error) {
        console.error('Error validando código');
    }
    return false;
}

// Función para forzar un nuevo código
async function forceNewCode() {
    // REMOVIDO: console.log('Forzando generación de nuevo código...');
    await generateNewCode();
    updateSecurityCodeDisplay();
    syncTimer();
}

// Función para obtener información del código (debugging) - FUNCIÓN REMOVIDA COMPLETAMENTE
// Esta función exponía información sensible y debe ser eliminada en producción

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', async function() {
    // REMOVIDO: console.log('DOM cargado, inicializando código de seguridad...');
    await initializeCode();
    
    // Verificar si estamos en el panel de administrador
    if (document.getElementById('security-code') && document.getElementById('timer')) {
        // REMOVIDO: console.log('Panel de administrador detectado');
        updateSecurityCodeDisplay();
        startTimer();
        
        // Botón para generar nuevo código
        const codeElement = document.getElementById('security-code');
        if (codeElement && codeElement.parentElement) {
            const refreshButton = document.createElement('button');
            refreshButton.textContent = 'Generar Nuevo Código';
            refreshButton.className = 'btn btn-sm btn-secondary mt-2';
            refreshButton.onclick = forceNewCode;
            codeElement.parentElement.appendChild(refreshButton);
        }
    }
    // REMOVIDO: else { console.log('Página normal detectada, código inicializado en background'); }
});

// Exponer funciones globalmente
window.validateSecurityCode = validateSecurityCode;
window.forceNewCode = forceNewCode;
// REMOVIDO: window.getCodeInfo = getCodeInfo; (función eliminada)
