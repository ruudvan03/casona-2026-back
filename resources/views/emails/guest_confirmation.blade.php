<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #334155; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; border: 1px solid #e2e8f0; padding: 40px; border-radius: 16px; background-color: #ffffff; }
        .header-logo { text-align: center; margin-bottom: 25px; }
        h2 { color: #059669; font-size: 24px; font-weight: 800; margin-top: 0; }
        p { font-size: 15px; color: #475569; }
        .info-card { background: #f8fafc; border: 1px solid #f1f5f9; padding: 25px; border-radius: 12px; margin: 25px 0; }
        .info-title { font-weight: 800; text-transform: uppercase; font-size: 12px; color: #64748b; letter-spacing: 0.05em; margin-bottom: 15px; display: block; }
        ul { list-style: none; padding: 0; margin: 0; }
        li { margin-bottom: 10px; font-size: 14px; display: flex; align-items: center; }
        .label { font-weight: bold; color: #1e293b; width: 80px; display: inline-block; }
        .folio-badge { background-color: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 9999px; font-weight: 800; font-size: 12px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; margin-left: 5px; border: 1px solid transparent; }
        .badge-pool { background-color: #eff6ff; color: #2563eb; border-color: #dbeafe; }
        .badge-kitchen { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
        .highlight { color: #059669; font-weight: bold; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9; text-align: center; }
        .footer-note { font-size: 11px; color: #94a3b8; font-style: italic; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-logo">
            <h1 style="color: #064e3b; margin: 0; font-size: 20px; text-transform: uppercase; letter-spacing: 2px;">La Casona</h1>
            <div style="height: 2px; width: 50px; background: #059669; margin: 10px auto;"></div>
        </div>

        <h2>¡Hola, {{ $event->customer_name }}!</h2>
        <p>Gracias por interesarte en <strong>Palapa La Casona</strong>. Hemos recibido correctamente tu solicitud de reserva y ya estamos trabajando en ella.</p>
        
        <div class="info-card">
            <span class="info-title">Resumen de tu solicitud</span>
            <ul>
                <li><span class="label">Folio:</span> <span class="folio-badge">{{ $event->folio }}</span></li>
                <li><span class="label">Fecha:</span> <strong>{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d \d\e F, Y') }}</strong></li>
                <li><span class="label">Evento:</span> {{ $event->event_type }}</li>
                <li>
                    <span class="label">Servicios:</span> 
                    <span class="badge" style="background-color: #f1f5f9; color: #475569;">Palapa</span>
                    @if($event->include_pool) <span class="badge badge-pool">Alberca</span> @endif
                    @if($event->include_kitchen) <span class="badge badge-kitchen">Cocina</span> @endif
                </li>
            </ul>
        </div>

        <p><strong>¿Qué sigue ahora?</strong></p>
        <p>Nuestro equipo revisará los detalles y te contactará a la brevedad vía <span class="highlight">WhatsApp</span> al número <strong>{{ $event->customer_phone }}</strong> para proporcionarte la cotización final y las instrucciones para el pago del anticipo.</p>
        
        <div class="footer">
            <p style="margin-bottom: 5px;">📍 San Jacinto Amilpas, Oaxaca</p>
            <p class="footer-note">Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>