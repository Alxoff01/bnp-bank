<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Démarrer la session
session_start();
var_dump($_SESSION);
// Vérifier si les variables de session sont définies
if (isset($_SESSION['email']) && isset($_SESSION['destinataire']) && isset($_SESSION['montant']) && isset($_SESSION['iban']) && isset($_SESSION['date'])) {
    $email = $_SESSION['email'];
    $destinataire = $_SESSION['destinataire'];
    $montant = $_SESSION['montant'];
    $iban = $_SESSION['iban'];
    $date = $_SESSION['date'];

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Paramètres SMTP
        $mail->isSMTP();
        $mail->Host       = 'mail.infomaniak.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'service@ecomptees.site';
        $mail->Password   = 'Mercimaman2023@';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Destinataire
        $mail->setFrom('service@ecomptees.site', 'Service Client');
        $mail->addAddress($email, $destinataire);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = 'Transaction Reçue';
        $mail->Body    = 'Bonjour ' . htmlspecialchars($destinataire) . ',<br><br>' .
                         'Vous avez reçu ' . htmlspecialchars($montant) . ' EUR.<br>' .
                         'Détails de la transaction :<br>' .
                         'IBAN : ' . htmlspecialchars($iban) . '<br>' .
                         'Date : ' . htmlspecialchars($date) . '<br><br>' .
                         'Le montant sera crédité sur votre compte lorsque vous vous serez acquitté des frais de virement.';
        $mail->AltBody = 'Bonjour ' . htmlspecialchars($destinataire) . ',\n\n' .
                         'Vous avez reçu ' . htmlspecialchars($montant) . ' EUR.\n' .
                         'Détails de la transaction :\n' .
                         'IBAN : ' . htmlspecialchars($iban) . '\n' .
                         'Date : ' . htmlspecialchars($date) . '\n\n' .
                         'Le montant sera crédité sur votre compte lorsque vous vous serez acquitté des frais de virement.';

        // Envoi de l'e-mail
        $mail->send();
        echo 'E-mail envoyé avec succès';
    } catch (Exception $e) {
        echo "Échec de l'envoi de l'e-mail. Erreur: {$mail->ErrorInfo}";
    }
} else {
    echo 'Les données de session sont manquantes.';
}
?>
