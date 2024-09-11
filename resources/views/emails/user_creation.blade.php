<!DOCTYPE html>
<html>
<head>
    <title>Bienvenue sur notre plateforme</title>
</head>
<body>
    <h1>Bonjour {{ $user->prenom }} {{ $user->nom }},</h1>
    <p>Nous sommes ravis de vous accueillir sur notre plateforme.</p>
    <p>Vous trouverez en pièce jointe un PDF contenant vos informations ainsi qu'un QR code.</p>
    <p>Merci,</p>
    <p>L'équipe</p>
</body>
</html>
