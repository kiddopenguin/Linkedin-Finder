# LinkedIn Finder

Sistema web para geração automatizada de URLs de busca do LinkedIn a partir de arquivos Excel.

## 📋 Descrição

O LinkedIn Finder permite importar dados de arquivos Excel e gerar automaticamente URLs de busca personalizadas do LinkedIn, facilitando a prospecção e busca de contatos de forma escalável.

## ✨ Funcionalidades

- ✅ Upload de arquivos Excel (.xlsx, .xls, .csv)
- ✅ Configuração de URLs personalizadas com variáveis dinâmicas
- ✅ Detecção automática de colunas do Excel
- ✅ Suporte para nome e sobrenome separados
- ✅ Geração de arquivo Excel apenas com URLs
- ✅ Interface moderna e responsiva com Bootstrap 5
- ✅ Processamento em background via AJAX

## 🚀 Tecnologias

- **PHP** 7.4+
- **PhpSpreadsheet** - Manipulação de arquivos Excel
- **Bootstrap 5** - Interface responsiva
- **JavaScript** - Interatividade e AJAX

## 📦 Instalação

### Pré-requisitos

- PHP 7.4 ou superior
- Composer
- Servidor web (Apache/Nginx)

### Passo a Passo

1. Clone o repositório:
```bash
git clone https://github.com/seu-usuario/linkedin-finder.git
cd linkedin-finder
```

2. Instale as dependências:
```bash
composer install
```

3. Configure permissões das pastas:
```bash
chmod 755 uploads/
chmod 755 output/
```

4. Acesse via navegador:
```
http://localhost/linkedin-finder/
```

## 🎯 Como Usar

### 1. Configure as Variáveis de URL

Marque as variáveis que deseja usar na busca do LinkedIn:

- **Nome** - Nome completo da pessoa
- **Empresa** - Empresa atual
- **Cargo** - Título/cargo
- **Localização** - Cidade/região
- **Email** - Endereço de e-mail
- **Telefone** - Número de telefone

Você pode deixar os campos vazios (usa dados do Excel) ou preencher com valores fixos.

### 2. Importe o Arquivo Excel

Upload de arquivo com as colunas:
- `Nome` ou `Name`
- `Sobrenome` ou `Last Name` (opcional, será concatenado com Nome)
- `Empresa` ou `Company` ou `Associated Company (Primary)`
- `Cargo` ou `Title`
- `Email` ou `E-mail`
- `Telefone` ou `Phone`

### 3. Processe

Clique em "Processar e Gerar URLs" e aguarde o download do arquivo gerado.

### 4. Arquivo de Saída

O sistema gera um arquivo Excel (`linkedin_urls_YYYY-MM-DD_HHMMSS.xlsx`) contendo apenas uma coluna com as URLs prontas para uso.

## 📂 Estrutura do Projeto

```
linkedin-finder/
├── index.php              # Dashboard principal
├── upload.php             # Processa upload de arquivos
├── process.php            # Gera URLs a partir do Excel
├── composer.json          # Dependências do projeto
├── .gitignore            # Arquivos ignorados pelo Git
├── README.md             # Documentação
├── assets/               # CSS e JavaScript
│   ├── css/
│   └── js/
├── uploads/              # Arquivos temporários (ignorado)
├── output/               # Arquivos gerados (ignorado)
└── vendor/               # Dependências Composer (ignorado)
```

## 🔧 Configuração Avançada

### Mapeamento de Colunas

O sistema detecta automaticamente colunas por nomes em português e inglês:

```php
$columnMap = [
    'nome' => ['nome', 'name', 'primeiro nome', 'sobrenome'],
    'empresa' => ['empresa', 'company', 'associated company'],
    'cargo' => ['cargo', 'title', 'position', 'job'],
    // ...
];
```

### Personalizar Template de URL

As URLs seguem o padrão:
```
https://www.linkedin.com/search/results/people/?keywords={variáveis}
```

Exemplo:
```
https://www.linkedin.com/search/results/people/?keywords=João%20Silva%20Microsoft
```

## 🛡️ Segurança

- ✅ Validação de tipo de arquivo
- ✅ Limite de tamanho de upload (10MB)
- ✅ Sanitização de dados
- ✅ Limpeza automática de arquivos temporários
- ✅ Proteção contra path traversal

## 🐛 Troubleshooting

### Erro: "Arquivo não encontrado"
- Verifique se a sessão PHP está ativa
- Confirme permissões das pastas uploads/ e output/

### Erro: "Colunas não encontradas"
- Certifique-se que os cabeçalhos do Excel contêm: Nome, Empresa, etc.
- O sistema busca por variações em português e inglês

### Upload não funciona
- Verifique o `php.ini`:
  - `upload_max_filesize = 10M`
  - `post_max_size = 10M`

## 📄 Licença

MIT License - Sinta-se livre para usar e modificar.

## 👤 Autor

Desenvolvido para facilitar a prospecção no LinkedIn.

## 🤝 Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona MinhaFeature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abra um Pull Request

## 📞 Suporte

Para dúvidas ou problemas, abra uma issue no GitHub.

---

**Nota:** Este projeto não é afiliado ao LinkedIn. Use de acordo com os termos de serviço do LinkedIn.
