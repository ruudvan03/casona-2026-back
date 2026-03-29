<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reserva en La Casona</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #047857; padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 28px; font-weight: bold; font-style: italic; }
        .content { padding: 40px 30px; color: #334155; line-height: 1.6; }
        .folio-box { background-color: #ecfdf5; border: 2px dashed #34d399; text-align: center; padding: 20px; border-radius: 12px; margin: 30px 0; }
        .folio-box p { margin: 0; color: #065f46; font-size: 14px; text-transform: uppercase; font-weight: bold; }
        .folio-box h2 { margin: 10px 0 0 0; color: #047857; font-size: 32px; letter-spacing: 2px; }
        .details-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table td { padding: 12px 0; border-bottom: 1px solid #e2e8f0; }
        .details-table td:first-child { font-weight: bold; color: #64748b; width: 40%; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; }
        .btn { display: inline-block; background-color: #047857; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>LA CASONA</h1>
            <p style="margin-top: 10px; opacity: 0.9;">Hospedaje de Lujo</p>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $data['name'] }}</strong>,</p>
            <p>¡Gracias por elegir La Casona! Hemos recibido tu solicitud de reserva correctamente. A continuación, te compartimos los detalles de tu solicitud y tu número de folio.</p>
            
            <div class="folio-box">
                <p>Tu Folio de Reserva</p>
                <h2>{{ $folio }}</h2>
            </div>
            
            <h3 style="color: #0f172a; margin-bottom: 10px;">Detalles de la estancia:</h3>
            <table class="details-table">
                <tr>
                    <td>Llegada (Check-in):</td>
                    <td>{{ \Carbon\Carbon::parse($data['check_in'])->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Salida (Check-out):</td>
                    <td>{{ \Carbon\Carbon::parse($data['check_out'])->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Teléfono de contacto:</td>
                    <td>{{ $data['phone'] }}</td>
                </tr>
            </table>

            <p style="margin-top: 30px;"><strong>¿Qué sigue?</strong><br>
            Nos pondremos en contacto contigo al número de WhatsApp proporcionado a la brevedad para confirmar la disponibilidad y proporcionarte las instrucciones de pago.</p>
            
            <div style="text-align: center;">
                <a href="https://wa.me/9514401726" class="btn">Contactar por WhatsApp</a>
            </div>
        </div>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no respondas a esta dirección.</p>
            <p>&copy; {{ date('Y') }} La Casona. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>