// Funcionalidad para el panel de filtros colapsible
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleFilters');
    const filterPanel = document.getElementById('filterPanel');
    
    if (!toggleButton || !filterPanel) {
        return; // Salir si los elementos no existen
    }
    
    // Verificar si hay filtros activos al cargar la página
    const urlParams = new URLSearchParams(window.location.search);
    const hasActiveFilters = urlParams.has('tipo_pro') || 
                           urlParams.has('estado_construccion') || 
                           urlParams.has('estado_proyecto') || 
                           urlParams.has('con_zonas_sociales');
    
    // Si hay filtros activos, mostrar el panel automáticamente
    if (hasActiveFilters) {
        filterPanel.classList.add('show');
        toggleButton.classList.add('active');
        toggleButton.innerHTML = '<i class="fas fa-times"></i> Ocultar Filtros';
    }
    
    // Evento para mostrar/ocultar el panel de filtros
    toggleButton.addEventListener('click', function() {
        filterPanel.classList.toggle('show');
        toggleButton.classList.toggle('active');
        
        // Cambiar el texto del botón
        if (filterPanel.classList.contains('show')) {
            toggleButton.innerHTML = '<i class="fas fa-times"></i> Ocultar Filtros';
        } else {
            toggleButton.innerHTML = '<i class="fas fa-filter"></i> Filtros';
        }
    });
    
    // Animación suave para el scroll cuando se aplican filtros
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function() {
            // Mostrar un indicador de carga si es necesario
            const submitButton = filterForm.querySelector('button[type="submit"]');
            if (submitButton) {
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Aplicando...';
                submitButton.disabled = true;
                
                // Restaurar el botón después de un tiempo (por si hay errores)
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 5000);
            }
        });
    }
    
    // Funcionalidad adicional: cerrar panel con tecla Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && filterPanel.classList.contains('show')) {
            filterPanel.classList.remove('show');
            toggleButton.classList.remove('active');
            toggleButton.innerHTML = '<i class="fas fa-filter"></i> Filtros';
        }
    });
});

// Manejar el dropdown de zonas sociales
document.addEventListener('DOMContentLoaded', function() {
    const zonasCheckboxes = document.querySelectorAll('.zona-checkbox');
    const zonasSelectedSpan = document.getElementById('zonasSelected');
    
    function updateZonasDisplay() {
        const checkedBoxes = document.querySelectorAll('.zona-checkbox:checked');
        const count = checkedBoxes.length;
        
        if (count === 0) {
            zonasSelectedSpan.textContent = 'Seleccionar zonas sociales';
        } else if (count === 1) {
            zonasSelectedSpan.textContent = checkedBoxes[0].nextElementSibling.textContent.trim();
        } else {
            zonasSelectedSpan.textContent = `${count} zona(s) seleccionada(s)`;
        }
    }
    
    // Agregar event listeners a los checkboxes
    zonasCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateZonasDisplay);
    });
    
    // Evitar que el dropdown se cierre al hacer click en los items
    document.querySelectorAll('.dropdown-item-check').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // Inicializar el display
    updateZonasDisplay();
});