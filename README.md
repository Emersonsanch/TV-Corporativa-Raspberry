# 📺 TV Corporativa Raspberry

## 💡 Visão Geral do Projeto

O projeto **TV Corporativa Raspberry** é uma solução de gestão de conteúdo para exibição em telas remotas, desenvolvida em PHP com uma arquitetura MVC simples. Ele permite a gestão centralizada de conteúdo (vídeos, imagens, etc.) e a criação de playlists para exibição em telas, como TVs conectadas a um Raspberry Pi.

O sistema é ideal para comunicação interna em empresas, escolas ou qualquer ambiente que necessite de uma exibição dinâmica e programada de informações.

## ✨ Funcionalidades Principais

*   **Gestão de Conteúdo:** Interface administrativa para upload e gerenciamento de mídias (imagens e vídeos).
*   **Criação de Playlists:** Definição de sequências de exibição de conteúdo.
*   **Gestão de TVs/Pontos de Exibição:** Cadastro e controle das telas remotas.
*   **Player Dedicado:** Uma URL específica para o player que pode ser configurada em dispositivos como o Raspberry Pi para exibição em tela cheia (modo Kiosk).
*   **Estrutura MVC Simples:** Código organizado em `controllers`, `models` e `views`.

## 🛠️ Requisitos do Sistema

Para rodar o projeto, você precisará de um ambiente de servidor web com suporte a PHP e MySQL.

*   **Servidor Web:** Apache ou Nginx
*   **Linguagem:** PHP (versão 7.4 ou superior)
*   **Banco de Dados:** MySQL/MariaDB

## 🚀 Instalação e Configuração

### 1. Configuração do Banco de Dados

1.  Crie um banco de dados MySQL com o nome `digital_signage`.
2.  Importe o esquema do banco de dados usando o arquivo `database_schema.sql`.

    ```bash
    mysql -u seu_usuario -p digital_signage < database_schema.sql
    ```

3.  Edite o arquivo `config.php` para ajustar as credenciais do banco de dados, se necessário.

    ```php
    // --- Configurações do Banco de Dados ---
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'digital_signage'); 
    define('DB_USER', 'root'); // Altere para seu usuário
    define('DB_PASS', ''); // Altere para sua senha
    // ...
    ```

### 2. Configuração da Aplicação

1.  Certifique-se de que o servidor web está configurado para usar o `router.php` como *front controller* (configuração de *pretty URLs*). O arquivo `.htaccess` fornecido deve funcionar para o Apache.
2.  Ajuste a URL base da aplicação no `config.php`:

    ```php
    // --- Configurações da Aplicação ---
    define('APP_NAME', 'TV Corporativa');
    define('APP_URL', 'http://seu_dominio_ou_ip'); // **IMPORTANTE:** Altere para o IP/Domínio correto
    // ...
    ```

### 3. Acesso ao Painel Administrativo

Após a configuração, você pode acessar o painel administrativo.

*   **URL de Acesso:** `http://seu_dominio_ou_ip/admin`
*   **Credenciais Padrão:**
    *   **E-mail:** `admin@portal.com`
    *   **Senha:** `admin123` (A senha está hasheada no `config.php` e é apenas para o primeiro acesso/teste)

## 🖥️ Configuração do Raspberry Pi (Ponto de Exibição)

Para usar um Raspberry Pi como ponto de exibição em modo Kiosk (tela cheia), siga as instruções abaixo. Este procedimento configura o Firefox para iniciar automaticamente em tela cheia com a URL do player da TV.

### 1. Criar o Arquivo de Autostart

Crie o diretório e o arquivo de autostart para o Firefox:

```bash
mkdir -p /home/pi/.config/autostart
nano /home/pi/.config/autostart/tv.desktop
```

Cole o seguinte conteúdo no arquivo `tv.desktop`:

```ini
[Desktop Entry]
Type=Application
Name=TV Corporativa
Exec=firefox --kiosk http://192.168.0.11/tv/tv-token-12345
X-GNOME-Autostart-enabled=true
```

> **⚠️ ATENÇÃO:** Altere a URL `http://192.168.0.11/tv/tv-token-12345` para a URL correta do seu player de TV.

### 2. Tornar o Arquivo Executável

Dê permissão de execução ao arquivo:

```bash
chmod +x /home/pi/.config/autostart/tv.desktop
```

### 3. Configurar Políticas do Firefox (Modo Kiosk)

Para garantir que o Firefox funcione de forma otimizada e sem interrupções (como atualizações ou telemetria), configure as políticas:

```bash
sudo mkdir -p /etc/firefox/policies
sudo nano /etc/firefox/policies/policies.json
```

Cole o seguinte conteúdo no arquivo `policies.json`:

```json
{
  "policies": {
    "DisableTelemetry": true,
    "DisableFirefoxStudies": true,
    "DisableFirefoxAccounts": true,
    "DisablePocket": true,
    "DontCheckDefaultBrowser": true,
    "DisableProfileImport": true,
    "OverrideFirstRunPage": "",
    "OverridePostUpdatePage": "",
    "DisableHardwareAcceleration": true,
    "Homepage": {
      "URL": "http://192.168.0.11/tv/tv-token-12345",
      "Locked": true
    }
  }
}
```

> **⚠️ ATENÇÃO:** Novamente, ajuste a `URL` dentro da seção `Homepage` para a URL correta do seu player de TV.

### 4. Reiniciar o Raspberry Pi

Após todas as configurações, reinicie o dispositivo para que o Firefox inicie automaticamente no modo Kiosk:

```bash
sudo reboot
```

## ☕ Me Pague um Café!

Se este projeto foi útil para você e sua empresa, considere me pagar um café! Seu apoio me motiva a continuar desenvolvendo e mantendo projetos de código aberto.

**Chave Pix:** `emersonsanches@hotmail.com`

Obrigado pelo apoio!
