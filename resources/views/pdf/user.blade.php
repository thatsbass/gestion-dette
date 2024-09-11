<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            /* display: flex;
            justify-content: center;
            align-items: center; */
            min-height: 100vh;
            margin: 0;
        }
        .card {
            max-width: 320px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            margin: 0 auto;
            margin-top: 110px;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #3b82f6; /* Primary Color */
        }
        .user-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: black;
            margin-top: 15px;
        }
        .user-role {
            color: #6b7280; /* Muted color */
            margin-bottom: 10px;
        }
        .qr-description {
            margin-top: 10px;
            color: #6b7280;
        }
        .qr-code {
            width: 128px;
            height: 128px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="card">
    <img class="profile-img" src="{{ $photoPath }}" alt="Profile Image">
    <h2 class="user-name">{{ $user->prenom }} {{ $user->nom }}</h2>
    <p class="user-role"></p>
    <p class="qr-description">Scannez le QR code pour ajouter mes détails à votre téléphone</p>
    <img class="qr-code" src="{{ $qrCodePath }}" alt="QR Code">
   
</div>

</body>
</html>
