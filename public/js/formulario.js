document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get("status");

    // Detecta si está dentro de un iframe
    const swalScope = (window !== window.top && window.top.Swal) ? window.top.Swal : Swal;

    if (status === "ok") {
        swalScope.fire({
            title: "Formulario enviado",
            text: "Gracias por contactarnos. Te responderemos pronto.",
            icon: "success",
            confirmButtonText: "Aceptar"
        }).then(() => {
            history.replaceState(null, "", window.location.pathname);
        });
    } else if (status === "error") {
        swalScope.fire({
            title: "Error",
            text: "No se pudo guardar tu información. Intenta más tarde.",
            icon: "error",
            confirmButtonText: "Aceptar"
        }).then(() => {
            history.replaceState(null, "", window.location.pathname);
        });
    } else if (status === "incompleto") {
        swalScope.fire({
            title: "Campos incompletos",
            text: "Por favor completa todos los campos.",
            icon: "warning",
            confirmButtonText: "Aceptar"
        }).then(() => {
            history.replaceState(null, "", window.location.pathname);
        });
    }
});
