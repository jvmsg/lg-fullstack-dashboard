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
