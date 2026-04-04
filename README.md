CampoFácil - Sistema de Gestão e Pós-Venda Agrícola
Descrição do Projeto

O CampoFácil é uma solução de software voltada para a otimização do fluxo de trabalho de vendedores e consultores no setor do agronegócio. O sistema centraliza a gestão de clientes, o controle de estoque de insumos e, fundamentalmente, a automação do pós-venda através de alertas inteligentes de recompra.

Esta versão 2.0 consolida a arquitetura do backend em PHP e introduz as bases para a transformação do projeto em um Progressive Web App (PWA), visando o uso em ambientes com conectividade limitada.
Principais Funcionalidades
Gestão Estratégica

    Dashboard Analítico: Visualização de indicadores de performance, totalização de clientes e produtos ativos.

    CRM Agrícola: Cadastro detalhado de fazendas com segmentação por tipo de atividade (Corte ou Leite).

    Catálogo de Insumos: Registro de produtos com definição de valor unitário e ciclo de consumo estimado em dias.

Automação de Vendas

    Alertas de Recompra: Motor de cálculo baseado na data da última venda e no ciclo de consumo, identificando clientes com necessidade imediata de reposição.

    Integração com Comunicação: Geração de gatilhos para contato direto via API do WhatsApp para agilização do fechamento de pedidos.

Infraestrutura Mobile

    Arquitetura PWA: Implementação de Service Workers e Manifesto para suporte à instalação em dispositivos móveis e melhoria no tempo de carregamento.

Especificações Técnicas
Backend e Banco de Dados

    Linguagem: PHP 8.x

    Persistência: MySQL / MariaDB

    Segurança: Implementação de Prepared Statements e criptografia de credenciais com algoritmo password_hash.

Frontend e Mobile

    Interface: HTML5 e CSS3 com suporte a temas dinâmicos (Dark Mode).

    Progressive Web App: Service Workers (sw.js) e Web App Manifest (manifest.json).

Estrutura do Repositório

    config/: Arquivos de parametrização e conexão com o banco de dados.

    core/: Lógica de negócio, incluindo o motor de alertas e gestão de vendas.

    auth/: Módulos de autenticação e controle de sessão de usuários.

    public/: Interface de usuário e ativos de estilo.

    bancodedados.sql: Script de criação e estruturação das tabelas.

Procedimentos de Instalação

    Clonagem do Ambiente
    Bash

    git clone https://github.com/jorgehenriqueEG/campofacil.git

    Configuração de Banco de Dados
    Importe o arquivo bancodedados.sql em seu servidor MySQL. Configure as credenciais de acesso no arquivo conexao.php.

    Deploy Local
    Certifique-se de que o ambiente possui o PHP 8.x configurado e aponte o diretório raiz para o servidor Apache ou Nginx.

Cronograma de Evolução (Roadmap)

    Hospedagem em Nuvem: Migração para instâncias Amazon EC2 (AWS) para disponibilidade 24/7.

    Sincronização Offline: Implementação de persistência local via IndexedDB para permitir registros em áreas sem cobertura de internet.

    Relatórios Avançados: Geração de documentos PDF para análise de performance mensal.

Licença e Autoria
Autoria

Projeto desenvolvido por Jorge Henrique (jorgehenriqueEG).
Licença

Este projeto está licenciado sob a MIT License.

Copyright (c) 2026 jorgehenriqueEG

A permissão é concedida, gratuitamente, a qualquer pessoa que obtenha uma cópia deste software e dos arquivos de documentação associados, para lidar com o software sem restrições, incluindo, sem limitação, os direitos de usar, copiar, modificar, mesclar, publicar, distribuir, sublicenciar e/ou vender cópias do Software. Para mais detalhes, consulte o arquivo LICENSE na raiz deste repositório.
