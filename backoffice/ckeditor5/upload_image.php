<?php
// Set the upload directory path where the uploaded images will be stored
//$uploadDirectory = '../assets/imgs_texto/';
$uploadDirectory = "C:\\HostingSpaces\\juvenalpaulino\\techneart.ipt.pt_JNfbKjaR\\data\\techneart\\assets\\imgs_texto\\";
$url = get_absolute_from_relative('../assets/imgs_texto/');

// Check if the directory exists
if (!is_dir($uploadDirectory)) {
    // Create the directory
    mkdir($uploadDirectory, 0777, true);
}

// Get the uploaded file
$uploadedFile = $_FILES['upload'];

// Generate a unique filename for the uploaded image
$filename = uniqid('image_') . '.' . pathinfo($uploadedFile['name'], PATHINFO_EXTENSION);

// Move the uploaded file to the specified directory
move_uploaded_file($uploadedFile['tmp_name'], $uploadDirectory . $filename);

// Construct the response with the image URL
$response = [
    'url' => $url . $filename
];

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

function get_absolute_from_relative($relativePath)
{
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['REQUEST_URI']);
    // Remover "../" do caminho relativo
    while (strpos($relativePath, "../") === 0) {
        $relativePath = substr($relativePath, 3);
        $path = dirname($path);
    }
    $absoluteUrl = "http://" . $host . $path . "/" . $relativePath;
    return $absoluteUrl;
}
