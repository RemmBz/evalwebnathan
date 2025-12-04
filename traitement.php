<?php
header('Content-Type: application/json; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contact_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de connexion à la base de données: ' . $conn->connect_error
    ]);
    exit();
}

$conn->set_charset("utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = isset($_POST['prenom']) ? htmlspecialchars(trim($_POST['prenom'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    
    if (!empty($prenom) && !empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("INSERT INTO contacts (prenom, email) VALUES (?, ?)");
            
            if ($stmt) {
                $stmt->bind_param("ss", $prenom, $email);
                
                if ($stmt->execute()) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Votre demande a été envoyée avec succès !',
                        'data' => [
                            'prenom' => $prenom,
                            'email' => $email
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Erreur lors de l\'enregistrement: ' . $stmt->error
                    ]);
                }
                $stmt->close();
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur de préparation de la requête: ' . $conn->error
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Email invalide'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tous les champs sont obligatoires'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode de requête invalide'
    ]);
}
$conn->close();
?>
