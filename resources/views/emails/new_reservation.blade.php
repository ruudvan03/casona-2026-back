<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #334155; line-height: 1.6; }
        .card { border: 1px solid #e2e8f0; padding: 30px; border-radius: 20px; max-width: 550px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { color: #065f46; font-weight: 800; font-size: 22px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 20px; border-bottom: 2px solid #ecfdf5; padding-bottom: 10px; }
        .detail { margin-top: 12px; font-size: 15px; display: flex; }
        .label { font-weight: 800; color: #64748b; width: 120px; text-transform: uppercase; font-size: 11px; margin-right: 10px; }
        .value { color: #1e293b; font-weight: 600; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; margin-right: 5px; }
        .badge-blue { background-color: #eff6ff; color: #2563eb; border: 1px solid #dbeafe; }
        .badge-amber { background-color: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
        .footer-text { margin-top: 25px; padding-top: 20px; border-top: 1px solid #f1f5f9; font-size: 13px; color: #94a3b8; text-align: center; }
        .btn { display: block; width: 100%; text-align: center; background-color: #065f46; color: #ffffff !important; padding: 14px; border-radius: 12px; text-decoration: none; font-weight: bold; margin-top: 25px; text-transform: uppercase; font-size: 12px; letter-spacing: 0.1em; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">🔔 Nueva Solicitud de Evento</div>
        <p style="margin-bottom: 20px;">Se ha registrado una nueva pre-reserva desde la Landing Page. Estos son los detalles:</p>
        
        <div class="detail">
            <span class="label">Folio:</span>
            <span class="value" style="color: #4f46e5;">{{ $event->folio }}</span>
        </div>

        <div class="detail">
            <span class="label">Cliente:</span>
            <span class="value">{{ $event->customer_name }}</span>
        </div>

        <div class="detail">
            <span class="label">Email:</span>
            <span class="value">{{ $event->customer_email }}</span>
        </div>

        <div class="detail">
            <span class="label">WhatsApp:</span>
            <span class="value">{{ $event->customer_phone }}</span>
        </div>

        <div class="detail">
            <span class="label">Fecha:</span>
            <span class="value">{{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}</span>
        </div>

        <div class="detail">
            <span class="label">Tipo:</span>
            <span class="value">{{ $event->event_type }}</span>
        </div>

        <div class="detail">
            <span class="label">Servicios:</span>
            <span class="value">
                <span class="badge" style="background-color: #f1f5f9; color: #475569;">Palapa</span>
                @if($event->include_pool) <span class="badge badge-blue">Alberca</span> @endif
                @if($event->include_kitchen) <span class="badge badge-amber">Cocina</span> @endif
            </span>
        </div>

        <a href="{{ url('/admin/events') }}" class="btn">Gestionar en Panel Administrativo</a>

        <div class="footer-text">
            Este es un aviso automático generado por el sistema de <strong>Palapa La Casona</strong>.
        </div>
    </div>
</body>
</html>