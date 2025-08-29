<?php
$to = 'bassolejustin7@gmail.com';
$subject = 'Test de la fonction mail() sur IONOS';
$message = 'Ceci est un message de test envoyé depuis la fonction mail() en PHP sur IONOS.';
$headers = 'From: action.cerivc@gmail.com' . "\r\n" .
           'Reply-To: action.cerivc@gmail.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email envoyé avec succès.';
} else {
    echo 'Échec de l\'envoi de l\'email.';
}
?>
