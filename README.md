# Gavio-Arquitetura-2.0

Este projeto é uma aplicação web desenvolvida para exibir e gerenciar projetos de arquitetura. Ele foi construído utilizando uma variedade de tecnologias modernas para proporcionar uma experiência de usuário agradável e eficiente.

## Tecnologias Utilizadas

### Backend

- **PHP**: Linguagem de programação utilizada para o desenvolvimento do backend.
- **Laravel**: Framework PHP utilizado para estruturar a aplicação, gerenciar rotas, controle de acesso, e mais.
- **MySQL**: Banco de dados utilizado para armazenar as informações dos projetos e outras entidades relacionadas.
- **Composer**: Gerenciador de dependências do PHP.
- **Intervention/Image**: Pacote utilizado para tratamento e diminuição do tamanho de imagens, aprimorando a performance e evitando o carregamento com arquivos muito pesados.

### Frontend

- **HTML5**: Linguagem de marcação utilizada para estruturar o conteúdo da aplicação.
- **CSS3**: Utilizado para estilizar a aplicação e torná-la visualmente atraente.
- **JavaScript**: Linguagem de programação utilizada para adicionar interatividade à aplicação.
- **Bootstrap**: Framework CSS utilizado para facilitar o design responsivo e componentes pré-estilizados.
- **Vite**: Ferramenta de build utilizada para desenvolvimento e construção do frontend.
  
### Outros

- **Blade Templates**: Engine de templates do Laravel utilizada para criar a interface do usuário.
- **Markdown**: Utilizado para criar templates de email.
- **Gmail SMTP**: Serviço de email utilizado para enviar emails a partir da aplicação.
- **Queue**: Utilizada para enviar emails de forma assíncrona.

## Funcionalidades

- **Exibição de Projetos**: A aplicação permite visualizar uma lista de projetos de arquitetura com detalhes sobre cada um.
- **Carrossel de Imagens**: Exibe as imagens dos projetos em um carrossel interativo.
- **Formulário de Contato**: Permite aos usuários enviar mensagens através de um formulário de contato. As mensagens são enviadas por email de forma assíncrona utilizando filas.
- **Paginação**: A lista de projetos é paginada para facilitar a navegação.

## Instalação

Para instalar e configurar o projeto localmente, siga os passos abaixo:

1. **Clone o repositório:**
   ```bash
   git clone <URL_DO_REPOSITORIO>
   cd gavio-arquitetura-2.0

2. **Instale as dependências do PHP:**
    ```bash
    composer install

3. **Instale as dependências do Node.js:**
    ```bash
    npm install

4. **Configure o arquivo .env:**
    ```bash
    cp .env.example .env

5. **Gere a chave da aplicação:**
    ```bash
    php artisan key:generate

6. **Configure o banco de dados:**
    ```bash
    No arquivo .env, ajuste as configurações de banco de dados (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD) conforme necessário.

7. **Execute as migrações do banco de dados:**
    ```bash
    php artisan migrate

7. **Se desejar, rode as seeds:**
    ```bash
    php artisan db:seed

9. **Inicie o servidor de desenvolvimento::**
    ```bash
    php artisan serve

10. **Inicie o Vite para o desenvolvimento do frontend:**
    ```bash
    npm run dev

    ## Uso

### Exibição de Projetos

A aplicação permite visualizar uma lista de projetos de arquitetura com detalhes sobre cada um.

### Carrossel de Imagens

Exibe as imagens dos projetos em um carrossel interativo.

### Formulário de Contato

Permite aos usuários enviar mensagens através de um formulário de contato. As mensagens são enviadas por email de forma assíncrona utilizando filas.

### Paginação

A lista de projetos é paginada para facilitar a navegação.

## Contribuição

Se você deseja contribuir para este projeto, por favor, faça um fork do repositório, crie uma nova branch para sua feature ou correção de bug, faça suas alterações e envie um pull request.

