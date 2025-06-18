@extends('layouts.app') 
@push('styles')
<link rel="stylesheet" href="{{ asset('css/estilo 2.css') }}">
@endpush

@section('title', 'Términos y Condiciones')

@section('content')
<div class="container my-5">
    <div class="bg-white p-5 rounded shadow border border-warning">
        <h1 class="mb-4 text-center">Términos y Condiciones de Uso</h1>

        <p><strong>Última actualización:</strong> junio 2025</p>

        <h2>1. Aceptación de términos</h2>
        <p>Al enviar este formulario, usted acepta los presentes Términos y Condiciones de uso del sitio web y otorga su consentimiento para el tratamiento de sus datos personales conforme a la Ley 1581 de 2012 y el Decreto 1377 de 2013.</p>

        <h2>2. Finalidad del uso de la información</h2>
        <p>Los datos personales recolectados a través de este formulario serán utilizados únicamente para:</p>
        <ul>
            <li>Contactar al usuario para atender su solicitud o comentario.</li>
            <li>Envío de información relacionada con los servicios o proyectos ofrecidos.</li>
            <li>Fines administrativos o estadísticos internos.</li>
        </ul>

        <h2>3. Protección de datos personales</h2>
        <p>En cumplimiento de la Ley 1581 de 2012, informamos que los datos personales proporcionados serán almacenados de manera segura, y no serán vendidos, cedidos ni compartidos con terceros sin su consentimiento previo, salvo requerimiento legal.</p>

        <p>Usted tiene derecho a conocer, actualizar, rectificar y suprimir sus datos personales, así como a revocar la autorización otorgada para su tratamiento, enviando una solicitud al correo electrónico del responsable del tratamiento.</p>

        <h2>4. Responsable del tratamiento</h2>
        <p><strong>Nombre del responsable:</strong> [MYR Proyectos y Construcciones S.A.S]</p>
        <p><strong>Correo de contacto:</strong> [myrproyectosycontrucciones@gmail.com]</p>
        <p><strong>Dirección:</strong> [cra 2 sur altos de rincon de varsovia]</p>

        <h2>5. Vigencia de la autorización</h2>
        <p>La autorización para el uso de datos personales estará vigente por el tiempo necesario para cumplir con las finalidades del tratamiento, o hasta que el titular solicite su eliminación.</p>

        <h2>6. Modificaciones</h2>
        <p>Nos reservamos el derecho de actualizar estos términos y condiciones en cualquier momento. Las modificaciones se publicarán en esta misma página.</p>

        <p class="mt-4"><strong>Gracias por confiar en nosotros.</strong></p>

        <div class="text-center mt-4">
        <a href="{{ url('/') }}" class="btn btn-warning">Volver al inicio</a>

        </div>
    </div>
</div>
@endsection
