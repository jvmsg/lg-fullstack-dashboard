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

> Nota: Estava nos planos iniciais preparar um comando para o `artisan` capaz de criar o banco e executar as migrations e o seed. Porém, para evitar problemas com autenticação no MySQL a criação do banco ficou comentada no código e é preciso criar o banco manualmente de acordo com o valor `DB_DATABASE` do `.env`.

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
