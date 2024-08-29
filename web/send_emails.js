const express = require('express');
const nodemailer = require('nodemailer');
const mysql = require('mysql');
const app = express();

// MySQL Verbindungssetup
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'shop'
});

// E-Mail-Sende-Funktion
async function sendEmail(toEmail, subject, htmlContent) {
    let transporter = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: process.env.EMAIL_USER,
            pass: process.env.EMAIL_PASS
        }
    });

    let mailOptions = {
        from: process.env.EMAIL_USER,
        to: toEmail,
        subject: subject,
        html: htmlContent
    };

    try {
        let info = await transporter.sendMail(mailOptions);
        console.log('Email sent: ' + info.response);
    } catch (error) {
        console.error('Error sending email: ' + error);
    }
}

// Kaufroute
app.post('/purchase', (req, res) => {
    const userId = req.session.userId;
    const productId = req.body.productId;

    if (!userId) {
        return res.status(401).send('User not logged in');
    }

    // Bestellung in der Datenbank speichern
    const query = 'INSERT INTO orders (user_id, product_id, order_date, total_amount) VALUES (?, ?, NOW(), ?)';
    const totalAmount = 100; // Beispiel: Berechne den Gesamtbetrag
    db.query(query, [userId, productId, totalAmount], (err, result) => {
        if (err) throw err;

        // Benutzerinformationen aus der Datenbank abrufen
        const emailQuery = 'SELECT email, name FROM users WHERE id = ?';
        db.query(emailQuery, [userId], async (err, userResult) => {
            if (err) throw err;

            const userEmail = userResult[0].email;
            const userName = userResult[0].name;
            const orderId = result.insertId;
            const orderDate = new Date().toLocaleDateString('de-DE');

            // HTML-Vorlage mit Benutzerdaten ausfüllen
            let emailContent = `
                <!DOCTYPE html>
                <html lang="de">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Bestellbestätigung</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            color: #333;
                            background-color: #f9f9f9;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 600px;
                            margin: 0 auto;
                            background-color: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        }
                        h1 {
                            color: #333;
                        }
                        p {
                            line-height: 1.6;
                        }
                        .footer {
                            margin-top: 20px;
                            font-size: 14px;
                            color: #666;
                        }
                    </style>
                </head>
                <body>
                <div class="container">
                    <h1>Vielen Dank für Ihre Bestellung, ${userName}!</h1>
                    <p>Ihre Bestellung #${orderId} wurde erfolgreich aufgegeben. Hier sind die Details Ihrer Bestellung:</p>
                    <p><strong>Bestelldatum:</strong> ${orderDate}</p>
                    <p><strong>Gesamtbetrag:</strong> €${totalAmount}</p>
                    <p>Wir werden Sie benachrichtigen, sobald Ihre Bestellung versendet wird.</p>
                    <p>Vielen Dank, dass Sie bei uns eingekauft haben!</p>
                    <div class="footer">
                        <p>Mit freundlichen Grüßen,<br>Das Team von GOOD DON'T DIE</p>
                        <p><a href="https://www.gooddontdie.com">www.gooddontdie.com</a></p>
                    </div>
                </div>
                </body>
                </html>`;

            // Bestellbestätigung per E-Mail senden
            await sendEmail(
                userEmail,
                'Bestellbestätigung',
                emailContent
            );

            // Rückmeldung an den Kunden
            res.send('Bestellung abgeschlossen und Bestätigungsmail gesendet.');
        });
    });
});

// Server starten
app.listen(3000, () => {
    console.log('Server läuft auf Port 3000');
});