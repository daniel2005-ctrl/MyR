// Variables globales
let currentSecurityCode = '';
let timerInterval;
let codeExpiresAt = null;

// Función para obtener el código actual del servidor
/**
 * Obtiene el código de seguridad actual del servidor
 * @returns {Promise<boolean>} True si se obtuvo exitosamente, false en caso contrario
 * @throws {Error} Si hay problemas de conectividad
 */
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
                return true;
            } else {
                console.error('Error del servidor:', data.message || 'Error desconocido');
            }
        } else {
            console.error('Error HTTP:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error de conexión obteniendo código del servidor:', error.message);
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
                return true;
            } else {
                console.error('Error del servidor generando código:', data.message || 'Error desconocido');
            }
        } else {
            console.error('Error HTTP generando código:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error de conexión generando código:', error.message);
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
    const success = await fetchCurrentCode();
    
    if (!success) {
        console.warn('No se pudo obtener código del servidor, intentando generar uno nuevo...');
        const generated = await generateNewCode();
        
        if (!generated) {
            console.error('No se pudo inicializar el código de seguridad. Verifique la conexión al servidor.');
            // Código de fallback temporal (solo para desarrollo)
            currentSecurityCode = '------';
            codeExpiresAt = new Date(Date.now() + 10 * 60 * 1000); // 10 minutos desde ahora
        }
    }
    
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

// Agregar después de las variables globales
let lastRequestTime = 0;
const REQUEST_COOLDOWN = 1000; // 1 segundo entre requests

// Función helper para rate limiting
function canMakeRequest() {
    const now = Date.now();
    if (now - lastRequestTime < REQUEST_COOLDOWN) {
        return false;
    }
    lastRequestTime = now;
    return true;
}

// Función helper para reintentos
async function retryWithBackoff(fn, maxRetries = 3) {
    for (let i = 0; i < maxRetries; i++) {
        try {
            const result = await fn();
            if (result) return result;
        } catch (error) {
            console.warn(`Intento ${i + 1} falló:`, error.message);
        }
        
        if (i < maxRetries - 1) {
            const delay = Math.pow(2, i) * 1000; // 1s, 2s, 4s
            await new Promise(resolve => setTimeout(resolve, delay));
        }
    }
    return false;
}

// Funciones de cache
function saveCodeToCache(code, expiresAt) {
    try {
        localStorage.setItem('securityCode', JSON.stringify({
            code,
            expiresAt: expiresAt.toISOString(),
            cachedAt: new Date().toISOString()
        }));
    } catch (error) {
        console.warn('No se pudo guardar en cache:', error.message);
    }
}

function loadCodeFromCache() {
    try {
        const cached = localStorage.getItem('securityCode');
        if (cached) {
            const data = JSON.parse(cached);
            const expiresAt = new Date(data.expiresAt);
            if (expiresAt > new Date()) {
                return { code: data.code, expiresAt };
            }
        }
    } catch (error) {
        console.warn('Error cargando cache:', error.message);
    }
    return null;
}

// Función para sanitizar y validar códigos
function sanitizeSecurityCode(code) {
    if (typeof code !== 'string') return null;
    
    // Remover caracteres no numéricos
    const cleaned = code.replace(/\D/g, '');
    
    // Validar longitud exacta
    if (cleaned.length !== 6) return null;
    
    return cleaned;
}

// Sistema de métricas simple
const metrics = {
    requestCount: 0,
    errorCount: 0,
    lastError: null,
    
    incrementRequest() { this.requestCount++; },
    incrementError(error) { 
        this.errorCount++; 
        this.lastError = { message: error.message, timestamp: new Date() };
    },
    
    getStats() {
        return {
            requests: this.requestCount,
            errors: this.errorCount,
            errorRate: this.requestCount > 0 ? (this.errorCount / this.requestCount * 100).toFixed(2) + '%' : '0%',
            lastError: this.lastError
        };
    }
};


// Función para mostrar estado de conexión
function updateConnectionStatus(isOnline) {
    const statusElement = document.getElementById('connection-status');
    if (statusElement) {
        statusElement.className = isOnline ? 'status-online' : 'status-offline';
        statusElement.textContent = isOnline ? '🟢 Conectado' : '🔴 Sin conexión';
    }
}

// Detectar cambios de conectividad
window.addEventListener('online', () => updateConnectionStatus(true));
window.addEventListener('offline', () => updateConnectionStatus(false));

// Sistema de notificaciones simple
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
