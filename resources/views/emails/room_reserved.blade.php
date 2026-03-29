<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva en La Casona</title>
    <style>
        /* Reset y Estilos Base */
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f0f2f5; padding-bottom: 40px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.08); margin-top: 40px; }
        
        /* Header con Gradiente de Lujo */
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 60px 20px; text-align: center; color: #ffffff; position: relative; }
        .header h1 { margin: 0; font-size: 32px; font-weight: 300; letter-spacing: 8px; text-transform: uppercase; }
        .header p { margin-top: 10px; opacity: 0.6; font-size: 11px; letter-spacing: 4px; text-transform: uppercase; font-weight: 600; }
        
        /* Contenido Principal */
        .content { padding: 45px 40px; color: #1e293b; line-height: 1.8; }
        .greeting { font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 15px; }
        .intro-text { color: #64748b; font-size: 15px; margin-bottom: 30px; }
        
        /* Folio Card */
        .folio-card { background-color: #f8fafc; border-radius: 20px; padding: 30px; text-align: center; border: 1px solid #e2e8f0; margin-bottom: 35px; }
        .folio-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; display: block; }
        .folio-number { font-size: 36px; font-weight: 900; color: #0f172a; letter-spacing: 2px; margin: 0; font-family: 'Courier New', Courier, monospace; }
        
        /* Tabla de Detalles */
        .details-grid { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-row td { padding: 15px 0; border-bottom: 1px solid #f1f5f9; }
        .label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; width: 40%; }
        .value { font-size: 15px; font-weight: 600; color: #334155; text-align: right; }
        .total-row td { border-bottom: none; padding-top: 25px; }
        .total-label { font-size: 14px; font-weight: 700; color: #0f172a; }
        .total-value { font-size: 24px; font-weight: 800; color: #059669; text-align: right; }

        /* Caja de Pago (Transferencia) */
        .payment-alert { background: linear-gradient(to right, #ecfdf5, #f0fdf4); border-left: 4px solid #10b981; padding: 25px; border-radius: 12px; margin: 30px 0; }
        .payment-title { color: #065f46; font-weight: 800; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: block; }
        .bank-data { width: 100%; font-size: 14px; color: #064e3b; }
        .bank-data td { padding: 4px 0; }
        
        /* Botón Pro */
        .btn-wrapper { text-align: center; margin-top: 40px; }
        .btn { background-color: #0f172a; color: #ffffff !important; text-decoration: none; padding: 18px 35px; border-radius: 14px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; display: inline-block; transition: all 0.3s ease; box-shadow: 0 10px 20px rgba(15,23,42,0.15); }
        
        /* Footer */
        .footer { padding: 40px; text-align: center; background-color: #f8fafc; border-top: 1px solid #f1f5f9; }
        .footer-logo { font-size: 16px; font-weight: 800; color: #0f172a; letter-spacing: 3px; margin-bottom: 10px; }
        .footer-address { font-size: 12px; color: #94a3b8; margin-bottom: 20px; }
        .copyright { font-size: 10px; color: #cbd5e1; text-transform: uppercase; letter-spacing: 1px; }

        /* Responsivo */
        @media only screen and (max-width: 480px) {
            .content { padding: 30px 20px; }
            .greeting { font-size: 18px; }
            .folio-number { font-size: 28px; }
            .header h1 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>LA CASONA</h1>
                <p>Private Boutique Residency</p>
            </div>
            
            <div class="content">
                <div class="greeting">¡Hola, {{ $reservation->customer_name }}!</div>
                <p class="intro-text">Es un placer saludarte. Hemos recibido tu solicitud de reserva y estamos preparando todo para brindarte una experiencia inigualable en Oaxaca.</p>
                
                <div class="folio-card">
                    <span class="folio-label">Confirmación de Trámite</span>
                    <h2 class="folio-number">{{ $reservation->folio }}</h2>
                </div>
                
                <table class="details-grid">
                    <tr class="details-row">
                        <td class="label">Residencia</td>
                        <td class="value">{{ $reservation->room->name }}</td>
                    </tr>
                    <tr class="details-row">
                        <td class="label">Check-in</td>
                        <td class="value">{{ \Carbon\Carbon::parse($reservation->check_in)->format('d M, Y') }}</td>
                    </tr>
                    <tr class="details-row">
                        <td class="label">Check-out</td>
                        <td class="value">{{ \Carbon\Carbon::parse($reservation->check_out)->format('d M, Y') }}</td>
                    </tr>
                    <tr class="details-row">
                        <td class="label">Garantía</td>
                        <td class="value">{{ $reservation->payment_method === 'transfer' ? 'Transferencia' : 'Efectivo' }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="total-label">Inversión Total</td>
                        <td class="total-value">${{ number_format($reservation->total_price, 2) }} <span style="font-size: 12px; color: #94a3b8;">MXN</span></td>
                    </tr>
                </table>

                @if($reservation->payment_method === 'transfer')
                <div class="payment-alert">
                    <span class="payment-title">Acciones Requeridas</span>
                    <p style="font-size: 13px; margin-bottom: 15px; color: #065f46;">Para confirmar tu estancia, por favor realiza el anticipo del 50% (<strong>${{ number_format($reservation->total_price / 2, 2) }} MXN</strong>) en las próximas 24 horas.</p>
                    
                    <table class="bank-data">
                        <tr><td><strong>Banco</strong></td><td style="text-align: right;">BBVA</td></tr>
                        <tr><td><strong>Titular</strong></td><td style="text-align: right;">Palapa La Casona</td></tr>
                        <tr><td><strong>CLABE</strong></td><td style="text-align: right; font-weight: 700;">0123 4567 8901 2345 67</td></tr>
                        <tr><td><strong>Concepto</strong></td><td style="text-align: right; color: #059669;"><strong>{{ $reservation->folio }}</strong></td></tr>
                    </table>
                </div>
                @else
                <div style="background-color: #f1f5f9; padding: 20px; border-radius: 12px; font-size: 13px; color: #475569; text-align: center;">
                    Has seleccionado <strong>Pago en Efectivo</strong>. Tu folio garantiza el precio, el pago se liquidará al llegar a la propiedad.
                </div>
                @endif

                <div class="btn-wrapper">
                    <a href="https://wa.me/529514401726?text={{ urlencode('Hola, envío comprobante de mi reserva en La Casona. Folio: ' . $reservation->folio) }}" class="btn">Confirmar por WhatsApp</a>
                </div>
            </div>
            
            <div class="footer">
                <div class="footer-logo">LA CASONA</div>
                <div class="footer-address">San Felipe del Agua, Oaxaca de Juárez, México.</div>
                <div class="copyright">&copy; {{ date('Y') }} Boutique Residency. Reservados todos los derechos.</div>
                <p style="font-size: 9px; color: #cbd5e1; margin-top: 20px; text-transform: uppercase;">Este mensaje es confidencial y generado automáticamente por nuestro sistema de gestión académica y de hospedaje.</p>
            </div>
        </div>
    </div>
</body>
</html>