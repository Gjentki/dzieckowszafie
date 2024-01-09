<?php
$target_dir = "uploads/";

// Iteracja przez każdy przesłany plik
foreach ($_FILES["fileToUpload"]["name"] as $key => $name) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$key]);
    $uploadOk = 1;

    // Dozwolone formaty plików
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    // Rozszerzenie pliku przesłanego przez użytkownika
    $extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Sprawdzenie czy rozszerzenie pliku znajduje się w tablicy dozwolonych formatów
    if (!in_array($extension, $allowedExtensions)) {
        echo "Nie obsługujemy tego";
        $uploadOk = 0;
    } else {
        // Sprawdzenie czy plik to zdjęcie
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"][$key]);
        if ($check === false) {
            echo "Plik " . $name . " nie jest zdjęciem.<br>";
            $uploadOk = 0;
        }

        // Sprawdzenie czy plik już istnieje
        if (file_exists($target_file)) {
            echo "Plik " . $name . " już istnieje.<br>";
            $uploadOk = 0;
        }

        // Sprawdzenie wielkości pliku
        if ($_FILES["fileToUpload"]["size"][$key] > 500000000) {
            echo "Plik " . $name . " jest za duży.<br>";
            $uploadOk = 0;
        }

        // Sprawdzenie, czy wszystkie warunki są spełnione
        if ($uploadOk == 0) {
            echo "Plik " . $name . " nie został dodany.<br>";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$key], $target_file)) {
                echo "Plik " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"][$key])) . " został przesłany pomyślnie.<br>";
            } else {
                echo "Coś poszło nie tak podczas przesyłania pliku " . $name . ". Spróbuj ponownie.<br>";
            }
        }
    }
}
?>
