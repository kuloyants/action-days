<?php
function getRegistrationSuccessMail($firstname, $surname, $lang) {

    switch ($lang) {
        case 'de':
            $mail = "
Servus {$firstname} {$surname},

danke für Deine Anmeldung für die ActionDays 2015 in Fürstenfeldbruck.

Wir weisen darauf hin, dass der Startplatz erst nach Geldeingang gesichert ist!

Das Startgeld:
60 € bei Geldeingang bis 27.04.2015
80 € bei Geldeingang ab 28.04.2015

Kontoverbindung:
BSV Playhouse Fürstenfeldbruck e.V.
Konto Nr.: 31817885
IBAN: DE59700530700031817885
Sparkasse Fürstenfeldbruck

Bei der Überweisung bitte Name, Gruppe (Freitag oder Samstag) und Uhrzeit angeben.

Auf unserer Seite erfährst Du alle Informationen rund um das Turnier: http://action-days.de

Auch auf Facebook sind wir aktiv und freuen uns auf Deinen Besuch:
https://www.facebook.com/pages/BSV-Playhouse-F%C3%BCrstenfeldbruck-eV/296125537184245
https://www.facebook.com/events/412495518910437/

Viele Grüße und bis bald,
Dein ActionDays Team
";
            break;
        case 'en':
            $mail = "
Hello {$firstname} {$surname},

Thank you for your registration for the 2nd Playhouse Fürstenfeldbruck ActionDays!

We point out that the registration is only secured after payment of the entry fee.

Entry fee:
60 € cash receipt until April 27th 2015
80 € cash receipt from April 28th 2015

Bank account: Sparkasse Fuerstenfeldbruck
Account holder: BSV Playhouse Fürstenfeldbruck e.V.
Account-No. 31817885
IBAN: DE59700530700031817885

Upon payment please provide your name, group (Friday or Saturday) and time.

On our website you can find out all the information about the tournament:
http://action-days.de

You will find us on Facebook as well:
https://www.facebook.com/pages/BSV-Playhouse-F%C3%BCrstenfeldbruck-eV/296125537184245
https://www.facebook.com/events/412495518910437/

We are looking forward for your visit!

Best regards,
Your ActionDays team
";
            break;
        default:
            throw new Exception("unknown lang: {$lang} given");
    }
    return $mail;
}

function getRegistrationSuccessSubject($lang) {
    switch ($lang) {
        case 'de':
            $subject = 'Anmeldung ActionDays 2015';
            break;
        case 'en':
            $subject = 'Registration for the ActionDays 2015';
            break;
        default:
            throw new Exception("unknown lang: {$lang} given");
    }

    return $subject;
}
