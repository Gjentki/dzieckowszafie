<?php
$uploadsDirectory = "uploads/";
$thumbnailsDirectory = "thumbnails/";

// Jeśli folder thumbnails nie istnieje, utwórz go
if (!file_exists($thumbnailsDirectory)) {
    mkdir($thumbnailsDirectory, 0777, true);
}

$images = array_diff(scandir($uploadsDirectory), array('..', '.'));

foreach ($images as $image) {
    $extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $thumbnailPath = $thumbnailsDirectory . $image;
        createThumbnail($uploadsDirectory . $image, $thumbnailPath);
    }
}

echo json_encode(array_values($images));

function createThumbnail($src, $dest) {
    $thumbnailWidth = 150; // Szerokość miniatury
    $thumbnailHeight = 150; // Wysokość miniatury

    // Pobierz informacje o pliku obrazu
    $imageInfo = getimagesize($src);
    $imageType = $imageInfo[2]; // Typ obrazu

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($src);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($src);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($src);
            break;
        default:
            // W przypadku plików JPG/JPEG/PNG/GIF, a inne typy plików zostaną pominięte
            return;
    }

    // Stwórz miniaturę
    $thumb = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

    // Kopiuj i zmniejsz obraz do rozmiaru miniatury
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, $imageInfo[0], $imageInfo[1]);

    // Zapisz miniaturę do pliku docelowego (format zależny od formatu oryginalnego obrazu)
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $dest);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $dest);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $dest);
            break;
    }

    // Zniszcz zasoby
    imagedestroy($thumb);
    imagedestroy($source);
}

?>
