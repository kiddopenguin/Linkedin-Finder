<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LinkedIn Finder - Dashboard</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #0077b5;
            --secondary-color: #00a0dc;
        }
        
        body {
            background-color: #f4f6f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 24px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 2px solid #e9ecef;
            font-weight: 600;
            color: #2c3e50;
            padding: 1.25rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 12px 30px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #005885;
            border-color: #005885;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .variable-group {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }
        
        .variable-group.active {
            background-color: #e7f3ff;
            border-color: var(--primary-color);
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .url-preview {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            font-family: monospace;
            word-break: break-all;
        }
        
        .progress {
            height: 25px;
            display: none;
        }
        
        #fileInfo {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-linkedin"></i> LinkedIn Finder Dashboard
            </span>
            <span class="text-white">
                <i class="bi bi-calendar3"></i> <?php echo date('d/m/Y'); ?>
            </span>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Card: Configurar URL Personalizada -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-link-45deg"></i> Configurar URL Personalizada do LinkedIn
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-4">Selecione as opções que deseja usar no link e preencha com os dados específicos ou deixe vazio para usar os dados do Excel:</p>
                        
                        <form id="urlConfigForm">
                            <div class="row">
                                <!-- Variável: Nome -->
                                <div class="col-md-6">
                                    <div class="variable-group" id="group_nome">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input variable-checkbox" type="checkbox" value="nome" id="var_nome" checked>
                                            <label class="form-check-label fw-bold" for="var_nome">
                                                <i class="bi bi-person"></i> Nome Completo
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="input_nome" placeholder="Ex: {nome} - será substituído pelos dados do Excel">
                                    </div>
                                </div>

                                <!-- Variável: Empresa -->
                                <div class="col-md-6">
                                    <div class="variable-group" id="group_empresa">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input variable-checkbox" type="checkbox" value="empresa" id="var_empresa" checked>
                                            <label class="form-check-label fw-bold" for="var_empresa">
                                                <i class="bi bi-building"></i> Empresa
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="input_empresa" placeholder="Ex: {empresa} - será substituído pelos dados do Excel">
                                    </div>
                                </div>

                                <!-- Variável: Cargo -->
                                <div class="col-md-6">
                                    <div class="variable-group" id="group_cargo">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input variable-checkbox" type="checkbox" value="cargo" id="var_cargo">
                                            <label class="form-check-label fw-bold" for="var_cargo">
                                                <i class="bi bi-briefcase"></i> Cargo/Título
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="input_cargo" placeholder="Ex: {cargo} - será substituído pelos dados do Excel" disabled>
                                    </div>
                                </div>

                                <!-- Variável: Localização -->
                                <!-- <div class="col-md-6">
                                    <div class="variable-group" id="group_localizacao">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input variable-checkbox" type="checkbox" value="localizacao" id="var_localizacao">
                                            <label class="form-check-label fw-bold" for="var_localizacao">
                                                <i class="bi bi-geo-alt"></i> Localização
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="input_localizacao" placeholder="Ex: {localizacao} - será substituído pelos dados do Excel" disabled>
                                    </div>
                                </div> -->

                                <!-- Variável: Email -->
                                <div class="col-md-6">
                                    <div class="variable-group" id="group_email">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input variable-checkbox" type="checkbox" value="email" id="var_email">
                                            <label class="form-check-label fw-bold" for="var_email">
                                                <i class="bi bi-envelope"></i> E-mail
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="input_email" placeholder="Ex: {email} - será substituído pelos dados do Excel" disabled>
                                    </div>
                                </div>

                                <!-- Variável: Telefone -->
                                <!-- <div class="col-md-6">
                                    <div class="variable-group" id="group_telefone">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input variable-checkbox" type="checkbox" value="telefone" id="var_telefone">
                                            <label class="form-check-label fw-bold" for="var_telefone">
                                                <i class="bi bi-telephone"></i> Telefone
                                            </label>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="input_telefone" placeholder="Ex: {telefone} - será substituído pelos dados do Excel" disabled>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Preview da URL -->
                            <div class="mt-4">
                                <label class="form-label">Preview da URL Gerada:</label>
                                <div class="url-preview" id="urlPreview">
                                    https://www.linkedin.com/search/results/people/?keywords=
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> As variáveis entre chaves {} serão substituídas pelos dados do Excel
                                </small>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card: Upload de Arquivo -->
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-file-earmark-excel"></i> Importar Arquivo Excel
                    </div>
                    <div class="card-body">
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <input type="file" class="form-control" id="fileInput" name="excel_file" accept=".xlsx,.xls,.csv">
                                    <small class="text-muted">Formatos aceitos: .xlsx, .xls, .csv</small>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary w-100" id="btnUpload">
                                        <i class="bi bi-cloud-upload"></i> Fazer Upload
                                    </button>
                                </div>
                            </div>
                            
                            <div id="fileInfo" class="alert alert-success mt-3">
                                <i class="bi bi-check-circle"></i>
                                <strong>Arquivo carregado:</strong> <span id="fileName"></span>
                                <span class="badge bg-success ms-2" id="fileSize"></span>
                            </div>
                            
                            <div class="progress mt-3" id="uploadProgress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card: Processar -->
                <div class="card">
                    <div class="card-body text-center">
                        <button type="button" class="btn btn-primary btn-lg" id="btnProcess" disabled>
                            <i class="bi bi-play-circle"></i> Processar e Gerar URLs
                        </button>
                        <p class="text-muted mt-2 mb-0">
                            <small>O sistema buscará automaticamente as colunas no Excel por nomes fixos</small>
                        </p>
                    </div>
                </div>

                <!-- Card: Ajuda -->
                <!-- <div class="card">
                    <div class="card-header">
                        <i class="bi bi-question-circle"></i> Como Usar
                    </div>
                    <div class="card-body">
                        <ol>
                            <li><strong>Configure a URL:</strong> Selecione as variáveis que deseja usar na busca do LinkedIn</li>
                            <li><strong>Importe o Excel:</strong> Faça upload do arquivo com os dados (o sistema buscará automaticamente as colunas)</li>
                            <li><strong>Processe:</strong> Clique em "Processar e Gerar URLs" para criar o arquivo final</li>
                            <li><strong>Download:</strong> Baixe o arquivo gerado com as URLs prontas</li>
                        </ol>
                        <div class="alert alert-info mb-0">
                            <strong>Colunas esperadas no Excel:</strong> Nome, Empresa, Cargo, Localização, Email, Telefone
                        </div>
                    </div>
                </div> -->

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Controle de checkboxes e inputs
        document.querySelectorAll('.variable-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const varName = this.value;
                const input = document.getElementById('input_' + varName);
                const group = document.getElementById('group_' + varName);
                
                if (this.checked) {
                    input.disabled = false;
                    group.classList.add('active');
                } else {
                    input.disabled = true;
                    input.value = '';
                    group.classList.remove('active');
                }
                
                updateUrlPreview();
            });
            
            // Inicializar estado
            if (checkbox.checked) {
                document.getElementById('group_' + checkbox.value).classList.add('active');
            }
        });

        // Atualizar preview da URL
        document.querySelectorAll('input[id^="input_"]').forEach(input => {
            input.addEventListener('input', updateUrlPreview);
        });

        function updateUrlPreview() {
            let url = 'https://www.linkedin.com/search/results/people/?keywords=';
            let params = [];
            
            document.querySelectorAll('.variable-checkbox:checked').forEach(checkbox => {
                const varName = checkbox.value;
                const inputValue = document.getElementById('input_' + varName).value;
                
                if (inputValue) {
                    params.push(inputValue);
                } else {
                    params.push('{' + varName + '}');
                }
            });
            
            url += params.join(' ');
            document.getElementById('urlPreview').textContent = url;
        }

        // Upload de arquivo
        document.getElementById('btnUpload').addEventListener('click', function() {
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            
            if (!file) {
                alert('Por favor, selecione um arquivo primeiro!');
                return;
            }
            
            // Mostrar progresso
            const progressBar = document.getElementById('uploadProgress');
            progressBar.style.display = 'block';
            progressBar.querySelector('.progress-bar').style.width = '50%';
            
            // Upload via AJAX
            const formData = new FormData();
            formData.append('excel_file', file);
            
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                progressBar.querySelector('.progress-bar').style.width = '100%';
                
                if (data.success) {
                    // Mostrar informações do arquivo
                    const fileName = document.getElementById('fileName');
                    const fileSize = document.getElementById('fileSize');
                    const fileInfo = document.getElementById('fileInfo');
                    
                    fileName.textContent = data.filename;
                    fileSize.textContent = formatBytes(file.size);
                    fileInfo.style.display = 'block';
                    
                    // Habilita botão processar
                    document.getElementById('btnProcess').disabled = false;
                    
                    setTimeout(() => {
                        progressBar.style.display = 'none';
                        progressBar.querySelector('.progress-bar').style.width = '0%';
                    }, 1000);
                } else {
                    alert('Erro ao fazer upload: ' + data.message);
                    progressBar.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao fazer upload do arquivo!');
                progressBar.style.display = 'none';
            });
        });

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Botão Processar
        document.getElementById('btnProcess').addEventListener('click', function() {
            // Coletar variáveis selecionadas
            const selectedVars = [];
            const varValues = {};
            
            document.querySelectorAll('.variable-checkbox:checked').forEach(cb => {
                selectedVars.push(cb.value);
                varValues[cb.value] = document.getElementById('input_' + cb.value).value;
            });
            
            if (selectedVars.length === 0) {
                alert('Selecione pelo menos uma variável!');
                return;
            }
            
            // Desabilitar botão durante processamento
            const btnProcess = document.getElementById('btnProcess');
            const originalText = btnProcess.innerHTML;
            btnProcess.disabled = true;
            btnProcess.innerHTML = '<i class="bi bi-hourglass-split"></i> Processando...';
            
            const formData = new FormData();
            formData.append('selected_vars', JSON.stringify(selectedVars));
            formData.append('var_values', JSON.stringify(varValues));
            
            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btnProcess.disabled = false;
                btnProcess.innerHTML = originalText;
                
                if (data.success) {
                    alert('Sucesso! ' + data.total_rows + ' URLs geradas.\\n\\nO download começará automaticamente.');
                    
                    // Fazer download do arquivo
                    window.location.href = data.download_url;
                    
                    // Resetar formulário após 2 segundos
                    setTimeout(() => {
                        document.getElementById('fileInput').value = '';
                        document.getElementById('fileInfo').style.display = 'none';
                        btnProcess.disabled = true;
                    }, 2000);
                } else {
                    alert('Erro ao processar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao processar arquivo!');
                btnProcess.disabled = false;
                btnProcess.innerHTML = originalText;
            });
        });

        // Inicializar preview
        updateUrlPreview();
    </script>
</body>
</html>