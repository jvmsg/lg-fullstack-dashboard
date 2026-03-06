# Desafio Técnico LG - João Víctor de Moraes Santos Gomes

Dashboard de eficiência de produção para a Planta A da LG Electronics, desenvolvido em Laravel seguindo as melhores práticas do framework.

## 📋 Sobre o Projeto

Sistema web que apresenta a eficiência de produção de 4 linhas de produtos (Geladeira, Máquina de Lavar, TV e Ar-Condicionado) durante o mês de Janeiro/2026.

**Funcionalidades principais:**

-   Visualização de todas as linhas de produção simultaneamente
-   Filtro por linha específica
-   Filtro por período (padrão: Janeiro/2026)
-   Cálculo automático de eficiência: `(unidades_produzidas - unidades_defeituosas) / unidades_produzidas × 100`
-   API REST para consumo dos dados
-   Interface responsiva com Bootstrap 4

## 🛠️ Tecnologias Utilizadas

-   **Backend:** Laravel 7.x
-   **Frontend:** Blade Templates + Bootstrap 4
-   **Banco de Dados:** MySQL 5.7+
-   **Server:** PHP 7.2.5+
-   **Ambiente:** Laragon (Windows)

## 🧩 Como o Frontend Foi Construído

O frontend foi construído com **Blade + Bootstrap 4 + Sass + JavaScript vanilla**, priorizando organização por componentes e reaproveitamento.

### 1. Estrutura de layout

-   O shell principal está em `resources/views/layouts/app.blade.php`.
-   Esse layout compõe a página com:
    -   `components/layout/sidebar.blade.php`
    -   `components/layout/topbar.blade.php`
    -   `components/layout/footer.blade.php`
-   O conteúdo de cada tela entra no `@yield('content')` dentro de `<main class="lg-content">`.

### 2. Dashboard componentizado

O dashboard foi dividido em componentes Blade para facilitar manutenção:

-   `components/dashboard/hero.blade.php`: resumo geral + filtros
-   `components/dashboard/metric-card.blade.php`: cards de eficiência por linha
-   `components/dashboard/chart-panel.blade.php`: painel do line chart
-   `components/dashboard/table-panel.blade.php`: tabela "Pulse diario"

Além disso, o conteúdo da área principal foi extraído para:

-   `resources/views/dashboard/partials/content.blade.php`

E a view principal `resources/views/dashboard/index.blade.php` mantém apenas o container:

-   `<div data-dashboard-content>...</div>`

### 3. Filtro sem recarregar sidenav/layout

Ao aplicar filtros, a página **não recarrega o layout completo**. Apenas a área do dashboard é atualizada.

Fluxo implementado:

1. O formulário com `data-dashboard-filter-form` é interceptado no `resources/js/app.js`.
2. O frontend faz `fetch` para a mesma rota com header `X-Dashboard-Partial: content`.
3. O controller (`DashboardController@index`) detecta esse header e retorna apenas `dashboard.partials.content`.
4. O HTML retornado substitui apenas `data-dashboard-content`.
5. O gráfico é reinicializado e o subtítulo da topbar é sincronizado.

Se houver qualquer erro na chamada assíncrona, o código faz fallback para navegação normal.

### 4. Gráfico com Chart.js

-   O Chart.js é instalado via npm e importado em `resources/js/app.js`.
-   O componente `chart-panel` injeta os dados em JSON no HTML.
-   O JS monta dinamicamente datasets por linha de produto e renderiza o line chart.

### 5. Estilos e responsividade

-   Os estilos ficam em `resources/sass/` organizados por camadas:
    -   `base/` (variáveis e estilos globais)
    -   `layout/` (shell, sidebar, topbar, footer)
    -   `pages/` (dashboard)
-   A responsividade usa grid/utilitários do Bootstrap e ajustes próprios em Sass.

### 6. Build frontend

O projeto usa **Laravel Mix** para compilar assets:

```bash
npm run dev
```

Arquivos gerados:

-   `public/css/app.css`
-   `public/js/app.js`
-   `public/mix-manifest.json`

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

-   ✅ Executar as migrations (criar tabelas)
-   ✅ Popular o banco com dados de teste (Janeiro/2026)
-   ✅ Preparar o dashboard para uso

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

**Solução Alternativa - Configurar MySQL 8.0:**

Se o problema persistir, você pode configurar o MySQL para usar `mysql_native_password` por padrão:

1. Abra o arquivo `my.ini` do MySQL no Laragon (Menu → MySQL → my.ini)
2. Localize a seção `[mysqld]`
3. Adicione a seguinte linha:

```ini
[mysqld]
mysql_native_password=ON
```

4. Salve o arquivo
5. Reinicie o MySQL no Laragon (Menu → MySQL → Restart)
6. Tente conectar novamente
