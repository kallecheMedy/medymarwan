<?php
session_start(); // Démarrer la session

// Configuration de la connexion à la base de données
$servername = "localhost"; // Remplace par ton serveur
$username = "root"; // Remplace par ton utilisateur de base de données
$password = ""; // Remplace par ton mot de passe
$dbname = "test"; // Remplace par le nom de ta base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête préparée pour éviter les injections SQL
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // L'utilisateur existe, on démarre la session
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Rediriger vers la page d'accueil ou tableau de bord
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/resources/labheader/css/academyLabHeader.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <form method="POST" action="">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" name="username" required autofocus>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Se connecter</button>
    </form>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
</body>
</html>
