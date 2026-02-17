<?php
session_start();
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Validação de dados recebidos
if (!isset($_POST['selected_vars']) || !isset($_POST['var_values'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados de configuração não enviados'
    ]);
    exit;
}

// RECEBE VIA POST
$selectVars = json_decode($_POST['selected_vars'], true);
$varValues = json_decode($_POST['var_values'], true);

// Validar se há variáveis selecionadas
if (empty($selectVars)) {
    echo json_encode([
        'success' => false,
        'message' => 'Selecione pelo menos uma variável para gerar as URLs'
    ]);
    exit;
}

// PEGA ARQUIVO DE SESSION
$filePath = $_SESSION['uploaded_file'] ?? null;

if (!$filePath || !file_exists($filePath)) {
    echo json_encode([
        'success' => false,
        'message' => 'Arquivo não encontrado'
    ]);
    exit;
}

try {
    // CARREGA ARQUIVO EXCEL
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();

    // Obtendo numero de linhas e colunas
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    
    // Verificar se há dados no arquivo
    if ($highestRow < 2) {
        echo json_encode([
            'success' => false,
            'message' => 'O arquivo não contém dados para processar'
        ]);
        exit;
    }

// Cabeçalhos
$headers = [];
for ($col = 'A'; $col <= $highestColumn; $col++) {
    $headers[$col] = $sheet->getCell($col . '1')->getValue();
}

// Mapeamento de nomes possíveis para cada coluna
$columnMap = [
    'nome' => ['nome', 'name', 'nome completo', 'full name', 'primeiro nome', 'first name', 'sobrenome', 'last name', 'surname'],
    'empresa' => ['empresa', 'company', 'organização', 'organization', 'companhia', 'associated company', 'associated company (primary)'],
    'cargo' => ['cargo', 'title', 'posição', 'position', 'job', 'função', 'job title'],
    'localizacao' => ['localização', 'localizacao', 'location', 'cidade', 'city', 'local'],
    'email' => ['email', 'e-mail', 'mail', 'correio'],
    'telefone' => ['telefone', 'phone', 'tel', 'celular', 'fone', 'contato', 'número de telefone', 'numero de telefone']
];

// Função para encontrar coluna por múltiplos nomes possíveis
function findColumnByName($headers, $possibleNames)
{
    foreach ($headers as $col => $header) {
        $headerLower = mb_strtolower(trim($header));
        foreach ($possibleNames as $name) {
            if (stripos($headerLower, mb_strtolower($name)) !== false) {
                return $col;
            }
        }
    }
    return null;
}

// Mapear colunas automaticamente
$columnIndexes = [];
$notFoundColumns = [];

foreach ($selectVars as $var) {
    if (isset($columnMap[$var])) {
        $foundCol = findColumnByName($headers, $columnMap[$var]);
        if ($foundCol) {
            $columnIndexes[$var] = $foundCol;
        } else {
            $notFoundColumns[] = $var;
        }
    }
}

// Busca especial para Nome e Sobrenome separados
$firstNameCol = null;
$lastNameCol = null;

foreach ($headers as $col => $header) {
    $headerLower = mb_strtolower(trim($header));
    
    // Busca coluna "Nome" (primeiro nome)
    if (in_array($headerLower, ['nome', 'name', 'primeiro nome', 'first name']) && !$firstNameCol) {
        $firstNameCol = $col;
    }
    
    // Busca coluna "Sobrenome"
    if (in_array($headerLower, ['sobrenome', 'last name', 'surname', 'apellido']) && !$lastNameCol) {
        $lastNameCol = $col;
    }
}

// Se encontrou nome e sobrenome separados e o usuário selecionou 'nome'
if ($firstNameCol && $lastNameCol && in_array('nome', $selectVars)) {
    $columnIndexes['nome'] = $firstNameCol;
    $columnIndexes['sobrenome'] = $lastNameCol; // Guardar para usar depois
}

// Avisar se alguma coluna não foi encontrada (mas continua o processamento)
if (!empty($notFoundColumns)) {
    error_log('Colunas não encontradas: ' . implode(', ', $notFoundColumns));
}

// Função para gerar URL
function buildUrl($rowData, $selectVars, $varValues)
{
    $baseUrl = 'https://www.linkedin.com/search/results/people/?keywords=';
    $params = [];
    
    foreach ($selectVars as $var) {
        $value = '';
        
        // Se tem valor fixo no input, usa ele
        if (!empty($varValues[$var])) {
            $value = $varValues[$var];
        } 
        // Senão, pega do Excel
        elseif (isset($rowData[$var]) && !empty($rowData[$var])) {
            $value = $rowData[$var];
        }
        
        if (!empty($value)) {
            $params[] = urlencode(trim($value));
        }
    }
    
    return $baseUrl . implode('%20', $params);
}

// Criar novo arquivo Excel apenas com URLs
$newSpreadsheet = new Spreadsheet();
$newSheet = $newSpreadsheet->getActiveSheet();

// Adicionar cabeçalho
$newSheet->setCellValue('A1', 'URL LinkedIn');

// Iterar pelas linhas do arquivo original (começar da linha 2 para pular cabeçalho)
$outputRow = 2;
for ($row = 2; $row <= $highestRow; $row++) {
    $rowData = [];
    
    // Extrair dados da linha atual
    foreach ($columnIndexes as $var => $colLetter) {
        $rowData[$var] = $sheet->getCell($colLetter . $row)->getValue();
    }
    
    // Se temos nome e sobrenome separados, concatenar
    if (isset($columnIndexes['sobrenome']) && isset($rowData['nome']) && isset($rowData['sobrenome'])) {
        $firstName = trim($rowData['nome']);
        $lastName = trim($rowData['sobrenome']);
        $rowData['nome'] = $firstName . ' ' . $lastName;
        // Remover sobrenome separado para não duplicar na URL
        unset($rowData['sobrenome']);
    }
    
    // Gerar URL para esta linha
    $url = buildUrl($rowData, $selectVars, $varValues);
    
    // Adicionar URL no novo arquivo (apenas a coluna A)
    $newSheet->setCellValue('A' . $outputRow, $url);
    $outputRow++;
}

// Salvar novo arquivo
$outputDir = 'output/';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

$outputFileName = 'linkedin_urls_' . date('Y-m-d_His') . '.xlsx';
$outputPath = $outputDir . $outputFileName;

$writer = new Xlsx($newSpreadsheet);
$writer->save($outputPath);

// Limpar arquivo temporário de upload
if (file_exists($filePath)) {
    unlink($filePath);
}
unset($_SESSION['uploaded_file']);

// Retornar resposta de sucesso
echo json_encode([
    'success' => true,
    'message' => 'URLs geradas com sucesso!',
    'download_url' => $outputPath,
    'filename' => $outputFileName,
    'total_rows' => ($highestRow - 1),
    'columns_found' => array_keys($columnIndexes),
    'columns_not_found' => $notFoundColumns ?? []
]);

} catch (Exception $e) {
    // Tratamento de erros
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar arquivo: ' . $e->getMessage()
    ]);
    exit;
}


