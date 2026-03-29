<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Evento - {{ $event->folio }}</title>
    <style>
        /* Optimización para una sola hoja */
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; line-height: 1.3; color: #333; margin: 0; }
        
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #065f46; padding-bottom: 5px; }
        .logo { max-height: 60px; margin-bottom: 5px; }
        .title { font-size: 16px; font-weight: bold; color: #065f46; text-transform: uppercase; margin: 0; }
        .folio { font-size: 12px; font-weight: bold; color: #666; }
        
        .section { margin-bottom: 12px; }
        .section-title { font-weight: bold; background: #f0fdf4; padding: 3px 8px; border-left: 4px solid #059669; margin-bottom: 5px; text-transform: uppercase; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        th { text-align: left; font-weight: bold; color: #4b5563; width: 30%; padding: 2px 0; }
        td { padding: 2px 0; }

        .clauses { font-size: 9.5px; text-align: justify; color: #4b5563; }
        .clauses ol { margin-top: 5px; padding-left: 20px; }
        .clauses li { margin-bottom: 2px; }

        .signature-container { margin-top: 30px; width: 100%; }
        .signature-box { width: 48%; display: inline-block; text-align: center; vertical-align: top; }
        .signature-line { border-top: 1px solid #000; margin: 40px 20px 8px 20px; }
        
        .footer { position: fixed; bottom: -10px; width: 100%; text-align: center; font-size: 8px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 3px; }
        .highlight { color: #065f46; font-weight: bold; }
        
        /* Estilo para iconos en el PDF */
        .icon-badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; margin-right: 5px; text-transform: uppercase; }
        .bg-blue { background-color: #eff6ff; color: #2563eb; border: 0.5px solid #bfdbfe; }
        .bg-amber { background-color: #fffbeb; color: #d97706; border: 0.5px solid #fef3c7; }
    </style>
</head>
<body>

    <div class="header">
        @if($logo)
            <img src="{{ $logo }}" class="logo">
        @endif
        <div class="title">Contrato de Arrendamiento para Eventos</div>
        <div class="folio">Folio: {{ $event->folio }}</div>
    </div>

    <div class="section">
        <div class="section-title">Datos del Arrendatario y Lugar</div>
        <table style="width: 100%;">
            <tr>
                <th>Cliente:</th>
                <td>{{ $event->customer_name }}</td>
                <th>Teléfono:</th>
                <td>{{ $event->customer_phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Lugar:</th>
                <td colspan="3">{{ $establishment }} - {{ $city }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Detalles del Evento y Áreas</div>
        <table>
            <tr>
                <th>Tipo de Evento:</th>
                <td><span class="highlight">{{ $event->event_type }}</span></td>
                <th>Horario:</th>
                <td>{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</td>
            </tr>
            <tr>
                <th>Fecha:</th>
                @php
                    $mesIngles = \Carbon\Carbon::parse($event->event_date)->format('F');
                    $fechaFormateada = \Carbon\Carbon::parse($event->event_date)->format('d') . ' de ' . ($meses[$mesIngles] ?? $mesIngles) . ' de ' . \Carbon\Carbon::parse($event->event_date)->format('Y');
                @endphp
                <td>{{ $fechaFormateada }}</td>
                <th>Áreas Incluidas:</th>
                <td>
                    <span class="icon-badge highlight">Palapa</span>
                    @if($event->include_pool) <span class="icon-badge bg-blue">Alberca</span> @endif
                    @if($event->include_kitchen) <span class="icon-badge bg-amber">Cocina</span> @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Información Financiera</div>
        <table style="background: #fafafa; border: 0.5px solid #eee;">
            <tr>
                <th style="padding-left: 10px;">Costo Total:</th>
                <td><strong>${{ number_format($event->total_price, 2) }} MXN</strong></td>
                <th>Anticipo Recibido:</th>
                <td class="highlight">${{ number_format($event->down_payment, 2) }} MXN</td>
            </tr>
            <tr>
                <th style="padding-left: 10px;">Saldo Pendiente:</th>
                <td colspan="3"><strong style="color: #dc2626; font-size: 12px;">${{ number_format($event->total_price - $event->down_payment, 2) }} MXN</strong></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Cláusulas de Servicio</div>
        <div class="clauses">
            <ol>
                <li><strong>USO:</strong> Espacio exclusivo para el fin mencionado. Prohibido el uso de sustancias ilícitas.</li>
                <li><strong>HORARIO:</strong> Se debe respetar la hora de término pactada (<span class="highlight">{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>). Excesos generarán cargos adicionales.</li>
                <li><strong>DEPÓSITO:</strong> El anticipo de <strong>${{ number_format($event->down_payment, 2) }}</strong> no es reembolsable en caso de cancelación por parte del cliente.</li>
                <li><strong>DAÑOS:</strong> El arrendatario cubrirá cualquier daño a mobiliario o instalaciones (@if($event->include_pool)incluyendo alberca, @endif @if($event->include_kitchen)cocina y @endif palapa).</li>
                <li><strong>LIQUIDACIÓN:</strong> El saldo pendiente deberá pagarse obligatoriamente al ingresar el día del evento.</li>
            </ol>
        </div>
    </div>

    <div class="signature-container">
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>{{ $representative }}</strong><br>Administración "La Casona"
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <strong>{{ $event->customer_name }}</strong><br>Arrendatario / Cliente
        </div>
    </div>

    <div class="footer">
        {{ $establishment }} - {{ $city }} | Generado el {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>