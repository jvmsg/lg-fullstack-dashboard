# Desafio Técnico LG - João Víctor de Moraes Santos Gomes

Dashboard de eficiência de produção para a Planta A da LG Electronics, desenvolvido em Laravel seguindo as melhores práticas do framework.

## 📋 Sobre o Projeto

Sistema web que apresenta a eficiência de produção de 4 linhas de produtos (Geladeira, Máquina de Lavar, TV e Ar-Condicionado) durante o mês de Janeiro/2026.

**Funcionalidades principais:**

- Visualização de todas as linhas de produção simultaneamente
- Filtro por linha específica
- Filtro por período (padrão: Janeiro/2026)
- Cálculo automático de eficiência: `(unidades_produzidas - unidades_defeituosas) / unidades_produzidas × 100`
- API REST para consumo dos dados
- Interface responsiva com Bootstrap 4

## 🛠️ Tecnologias Utilizadas

- **Backend:** Laravel 7.x
- **Frontend:** Blade Templates + Bootstrap 4
- **Banco de Dados:** MySQL 5.7+
- **Server:** PHP 7.2.5+
- **Ambiente:** Laragon (Windows)

## ⚙️ Setup Inicial

### 1. Clone o repositório (ou descompacte)

```bash
cd d:\laragon\www\
# Se estiver usando git:
git clone <repository-url> lg-fullstack-dashboard
cd lg-fullstack-dashboard
```

### 2. Instale as dependências

```bash
composer install
npm install
```

### 3. Configure o banco de dados no `.env`

Para simplificar o processo de preparação do ambiente durante a avaliação, o arquivo .env foi adicionado ao repositório. Nessa etapa o que importa são as seguintes variáveis:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lg_fullstack_dashboard
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Crie o banco de dados

Abra o **MySQL Console** do Laragon (Menu → MySQL → MySQL Console) e execute:

```sql
CREATE DATABASE lg_fullstack_dashboard;
```

> **Nota:** Caso tenha problemas de autenticação MySQL (erro 2054), consulte a seção [🔧 Solução de Problemas](#-solução-de-problemas) abaixo para instruções detalhadas de correção.

### 5. Execute o setup automático

```bash
php artisan db:setup
```

Este comando irá:

- ✅ Executar as migrations (criar tabelas)
- ✅ Popular o banco com dados de teste (Janeiro/2026)
- ✅ Preparar o dashboard para uso

**Alternativa manual:**

```bash
php artisan migrate --seed
```

## 🔧 Solução de Problemas

### Erro de Autenticação MySQL (SQLSTATE[HY000] [2054])

Se você encontrar o erro `"The server requested authentication method unknown to the client"` ao tentar conectar ao banco de dados, isso ocorre porque o MySQL 8.0+ usa o método de autenticação `caching_sha2_password` por padrão, que pode não ser suportado pelo PDO do PHP.

**Sintoma:**

```
SQLSTATE[HY000] [2054] The server requested authentication method unknown to the client
```

**Solução:**

Abra o **HeidiSQL** no Laragon (Menu → HeidiSQL) ou **phpMyAdmin** e execute o seguinte script SQL:

```sql
-- ========================================
-- Script de Correção MySQL para Laravel
-- ========================================
-- Execute este script no HeidiSQL ou phpMyAdmin
-- para corrigir o erro de autenticação 2054

-- 1. Criar o banco de dados se não existir
CREATE DATABASE IF NOT EXISTS lg_fullstack_dashboard;

-- 2. Selecionar o banco
USE lg_fullstack_dashboard;

-- 3. Alterar o método de autenticação do usuário root
-- (Isso corrige o erro "authentication method unknown to the client")
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';

-- 4. Atualizar privilégios
FLUSH PRIVILEGES;

-- 5. Verificar a alteração
SELECT user, host, plugin FROM mysql.user WHERE user = 'root';

-- ========================================
-- Resultado esperado:
-- user | host      | plugin
-- root | localhost | mysql_native_password
-- ========================================
```

Após executar o script, tente novamente conectar ao banco de dados.
