<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1.5cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10px; line-height: 1.5; color: #2d3748; }
        
        /* Encabezado Estilo Boutique */
        .header { text-align: center; border-bottom: 3px solid #065f46; padding-bottom: 12px; margin-bottom: 20px; }
        .logo { height: 65px; margin-bottom: 8px; }
        .hotel-name { font-size: 22px; font-weight: bold; color: #064e3b; text-transform: uppercase; letter-spacing: 1px; }
        .hotel-sub { font-size: 9px; color: #718096; font-style: italic; }

        .doc-title { text-align: center; font-size: 13px; font-weight: bold; color: #065f46; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }

        /* Contenedores de datos con énfasis en el Folio */
        .info-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 15px; background-color: #f8fafc; }
        .section-title { font-size: 11px; font-weight: bold; color: #065f46; text-transform: uppercase; margin-bottom: 8px; border-bottom: 1px solid #d1fae5; padding-bottom: 2px; }
        
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 0; }
        .label { font-weight: bold; color: #1a202c; width: 110px; }

        .clause { text-align: justify; margin-bottom: 8px; }
        .clause strong { color: #064e3b; }

        /* Firmas */
        .signature-section { margin-top: 40px; width: 100%; }
        .signature-box { text-align: center; width: 45%; }
        .line { border-top: 1px solid #2d3748; width: 85%; margin: 0 auto 6px; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #a0aec0; border-top: 1px solid #edf2f7; padding-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        @if($logo)
            <img src="{{ $logo }}" class="logo">
        @endif
        <div class="hotel-name">{{ $establishment }}</div>
        <div class="hotel-sub">{{ $city }} | Arrendador: {{ $representative }}</div>
    </div>

    <div class="doc-title">Contrato de Hospedaje Temporal</div>

    <div class="info-box">
        <div class="section-title">I. Información de la Reserva</div>
        <table>
            <tr>
                <td class="label" style="color: #065f46; font-size: 12px;">FOLIO OFICIAL:</td>
                <td style="color: #065f46; font-size: 12px; font-weight: bold;">{{ $reservation->folio }}</td>
                <td class="label">Huésped:</td>
                <td>{{ $reservation->customer_name }}</td>
            </tr>
            <tr>
                <td class="label">Teléfono:</td>
                <td>{{ $reservation->customer_phone }}</td>
                <td class="label">Email:</td>
                <td>{{ $reservation->customer_email }}</td>
            </tr>
        </table>
    </div>

    <div class="info-box">
        <div class="section-title">II. Detalles de la Estancia</div>
        <table>
            <tr>
                <td class="label">Espacio / Hab:</td>
                <td>{{ $reservation->room->name }}</td>
                <td class="label">Check-In:</td>
                <td>{{ $check_in->format('d/m/Y') }} (12:00 PM)</td>
            </tr>
            <tr>
                <td class="label">Monto por Noche:</td>
                <td>${{ number_format($reservation->room->price_per_night, 2) }}</td>
                <td class="label">Check-Out:</td>
                <td>{{ $check_out->format('d/m/Y') }} (11:00 AM)</td>
            </tr>
            <tr>
                <td class="label">Total Estancia:</td>
                <td colspan="3" style="font-weight: bold; color: #065f46;">
                    ${{ number_format($reservation->total_price, 2) }} MXN
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">III. Cláusulas Contractuales</div>
    
    <div class="clause">
        <strong>PRIMERA. OBJETO Y ALCANCE:</strong> EL ARRENDADOR otorga en arrendamiento temporal a EL HUÉSPED el uso de la habitación detallada anteriormente con los servicios inherentes al hospedaje conforme a las leyes de **{{ $city }}**. [cite: 12, 41]
    </div>

    <div class="clause">
        <strong>SEGUNDA. VIGENCIA Y TARIFA:</strong> La estancia inicia el día {{ $check_in->format('d/m/Y') }} a partir de las 12:00 pm y finalizará el día {{ $check_out->format('d/m/Y') }} a las 11:00 am. [cite: 15] Toda prolongación deberá solicitarse con 12 horas de anticipación sujeta a disponibilidad. [cite: 18]
    </div>

    <div class="clause">
        <strong>TERCERA. USO Y OCUPACIÓN:</strong> La habitación se utilizará exclusivamente para descanso temporal. [cite: 21] Queda prohibido su uso para actividades comerciales o actos ilícitos. [cite: 22] No se permiten mascotas. [cite: 25]
    </div>

    <div class="clause">
        <strong>CUARTA. DEPÓSITO Y DAÑOS:</strong> EL HUÉSPED es responsable de cualquier daño causado al mobiliario o estructura. [cite: 28] EL ARRENDADOR no se hace responsable por pérdida de objetos de valor fuera de resguardo. [cite: 30]
    </div>

    <div class="clause">
        <strong>QUINTA. CONVIVENCIA:</strong> EL HUÉSPED se obliga a mantener silencio entre las <strong>22:00 hrs y las 07:00 hrs</strong>. [cite: 32] Queda <strong>estrictamente prohibido fumar</strong> dentro de la habitación, incluyendo cigarrillos electrónicos. [cite: 33]
    </div>

    <div class="clause">
        <strong>SEXTA. TERMINACIÓN:</strong> EL ARRENDADOR podrá dar por terminado el contrato de manera inmediata, solicitando el desalojo sin derecho a reembolso, en caso de disturbios graves o incumplimiento de reglamento. [cite: 37, 38]
    </div>

    <div class="clause">
        <strong>SÉPTIMA. JURISDICCIÓN:</strong> Para cualquier controversia, las partes se someten a la jurisdicción de los tribunales competentes en <strong>{{ $city }}</strong>. [cite: 41]
    </div>

    <div style="text-align: center; margin-top: 10px; font-weight: bold; font-size: 9px; text-transform: uppercase;">
        HE LEÍDO Y ACEPTO LAS CLÁUSULAS DE ESTE CONTRATO
    </div>

    <table class="signature-section">
        <tr>
            <td class="signature-box">
                <div class="line"></div>
                <strong>EL ARRENDADOR</strong><br>
                {{ $representative }}
            </td>
            <td class="signature-box">
                <div class="line"></div>
                <strong>EL HUÉSPED</strong><br>
                {{ $reservation->customer_name }}
            </td>
        </tr>
    </table>

    <div class="footer">
        Palapa "La Casona" - Este contrato debe presentarse con el <strong>Folio: {{ $reservation->folio }}</strong> para cualquier aclaración.
    </div>

</body>
</html>