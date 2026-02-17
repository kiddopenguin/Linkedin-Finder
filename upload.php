<?php
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Validação se não houver arquivo
if (!isset($_FILES['excel_file'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Nenhum arquivo enviado',
    ]);
    exit;
}

$file = $_FILES['excel_file'];

// Validar a extensão

$extensõesAceitas = ['xlsx', 'xls', 'csv'];
$extensãoArquivo = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extensãoArquivo, $extensõesAceitas)) {
    echo json_encode([
        'success' => false,
        'message' => 'Formato inválido'
    ]);
    exit;
}

// Valida tamanho (10MB)

if ($file['size'] > 10 * 1024 * 1024) {
    echo json_encode([
        'success' => false,
        'message' => 'Arquivo muito grande!'
    ]);
    exit;
}

// Gera nome único, id e Path de Upload

$uploadDir = 'uploads/';
$nomeUnico = uniqid() . '_' . $file['name'];
$uploadPath = $uploadDir . $nomeUnico;

// Se conseguir mover o arquivo, salva o path novo na sessão
if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    $_SESSION['uploaded_file'] = $uploadPath;

    echo json_encode([
        'success' => true,
        'filename' => $file['name'],
        'path' => $uploadPath
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Falha ao salvar o arquivo!'
    ]);
}
