# 📱 Guia de Integração — Nexus WMS Mobile PWA

Este diretório contém os **13 arquivos** desenvolvidos e modificados para completar a implementação completa dos módulos de WMS Mobile (PWA) no Nexus WMS, totalmente integrados ao painel administrativo Filament e ao banco de dados MySQL.

Abaixo estão detalhadas todas as decisões arquiteturais, fluxos lógicos, regras de negócio aplicadas e o guia de dados fictícios para testes. Use este documento para instruir o outro chat/agente a fazer a mescla (merge) segura no projeto principal.

---

## 📁 Lista de Arquivos Modificados / Criados

Os arquivos estão organizados na estrutura de pastas idêntica à do Laravel:

```text
arquivos_modificados/
├── app/
│   └── Livewire/
│       └── Mobile/
│           ├── Conferencia.php
│           ├── Enderecamento.php      [NOVO]
│           ├── Inventario.php
│           ├── Separacao.php
│           └── Transferencia.php      [NOVO]
├── database/
│   └── seeders/
│       ├── PedidoSeeder.php
│       └── RecebimentoSeeder.php
└── resources/
    └── views/
        └── mobile/
            ├── layouts/
            │   └── app.blade.php
            ├── conferencia.blade.php
            ├── enderecamento.blade.php [NOVO]
            ├── inventario.blade.php
            ├── separacao.blade.php
            └── transferencia.blade.php [NOVO]
```

---

## 💡 Decisões de Arquitetura e Ideias Criadas

### 1. Leitor de Código de Barras Global & Simulador
- **Implementação**: Localizado em `resources/views/mobile/layouts/app.blade.php`.
- **Funcionamento**: Um modal central acionado pelo botão **Scan** na barra de navegação inferior.
- **Modos de Entrada**:
  1. **Câmera**: Usa a biblioteca `html5-qrcode` para acessar a câmera real do dispositivo (requer HTTPS ou localhost).
  2. **Digitar / Simular**: Um campo de texto focado automaticamente para simular leituras via digitação manual ou leitores USB/Bluetooth no desktop.
- **Comunicação (Livewire)**: Quando um código é lido ou digitado, o script emite o evento global Livewire `barcode-scanned` e fecha o modal. Cada componente Livewire escuta esse evento e executa a lógica de acordo com a tela ativa (Ex: adicionar item conferido, buscar endereço, etc.).
- **Feedback Sonoro**: Sintetiza um som de *beep* de WMS tradicional usando a **Web Audio API** do navegador (não requer arquivos de áudio externos).

### 2. Deep Linking (Vínculos Diretos entre Telas)
Para tornar a usabilidade extremamente fluida no celular, adicionamos suporte a parâmetros de consulta no método `mount()` das telas de movimentação:
- **`Transferencia.php`**: Aceita query parameters `enderecoId` e `loteId`. Se fornecidos, a tela inicializa pulando a seleção e indo direto para a tela de quantidade com a origem e lote travados.
- **`Inventario.php`**: Aceita query parameter `enderecoId`. Se fornecido, abre diretamente na tela de contagem física para aquele endereço.
- **Aplicação**: Isso permitiu criar atalhos de clique único ("Inventariar" e "Mover lote") dentro da tela de **Endereçamento**, interligando as telas instantaneamente.

---

## ⚙️ Detalhamento dos Módulos WMS Mobile

### 1. Módulo: Conferência (Recebimento de Carga)
- **Caminho**: `/app/conferencia`
- **Lógica**: Filtra recebimentos nos status `rascunho` e `em_conferencia`. Ao selecionar, altera o status do recebimento para `em_conferencia`.
- **Verificação**: O operador confere a lista de itens. Ele pode clicar em "Conferir" manualmente ou escanear o código do lote.
- **Progresso**: Uma barra de progresso no topo se atualiza visualmente com animações HSL. Ao atingir 100%, o botão **Finalizar Conferência** é liberado, atualizando o status do recebimento para `conferido` e retirando-o da fila ativa.

### 2. Módulo: Separação (Picking de Pedidos — Regra FEFO)
- **Caminho**: `/app/separacao`
- **Lógica**: Lista pedidos com status `pendente` e `em_separacao`. Ao selecionar um pedido, o sistema gera a rota de coleta seguindo estritamente a regra **FEFO** (First Expired, First Out — Primeiro que Vence, Primeiro que Sai), ordenando os lotes do produto pela data de validade mais antiga com saldo disponível.
- **Coleta**: O operador segue a ordem dos corredores mostrada na tela e escaneia o código do lote para confirmar a coleta de cada item. Ao finalizar, as quantidades coletadas são decrementadas dos respectivos lotes e o status do pedido vai para `conferido`.

### 3. Módulo: Inventário (Contagem de Estoque)
- **Caminho**: `/app/inventario`
- **Lógica**: O operador escaneia ou digita uma localização (Ex: `A-01-01`). O sistema exibe todos os lotes que constam no sistema para aquele endereço.
- **Contagem**: Permite ajustar as quantidades usando seletores rápidos (+1, +10, -1, -10) ou inserção manual.
- **Logs de Auditoria**: Ao salvar, o sistema grava uma atividade via `Spatie ActivityLog` com o tipo `inventario`, salvando a quantidade anterior, a quantidade contada e a divergência (diferença positiva ou negativa) para relatórios gerenciais no Admin.

### 4. Módulo: Transferência (Movimentação Interna)
- **Caminho**: `/app/transferencia`
- **Lógica**: Um assistente (Wizard) em 6 passos:
  1. `origem`: Escanear ou selecionar o endereço de origem (Ex: `A-01-01`).
  2. `lote_selecao`: Se houver mais de um lote no endereço, abre uma tela para escolher qual será movido.
  3. `quantidade`: O operador escolhe a quantidade (bloqueia valores acima do saldo atual).
  4. `destino`: Escanear ou selecionar o endereço de destino.
  5. `confirmar`: Resumo dos dados (De -> Para, Lote, Quantidade).
  6. `concluido`: Efetivação física no banco de dados.
- **Regra de Divisão de Lotes**: Ao transferir, o sistema faz o decremento na origem. No destino, ele verifica se já existe um lote com o **mesmo código de lote**. Se sim, ele soma a quantidade; se não, cria um novo registro de lote naquele endereço herdando as datas de fabricação e validade, garantindo a rastreabilidade do estoque.
- **Histórico**: Grava log detalhado da movimentação de endereços no Spatie ActivityLog.

### 5. Módulo: Endereçamento (Consulta de Estoque)
- **Caminho**: `/app/enderecamento`
- **Lógica**: Possui duas abas dinâmicas de busca:
  - **Consulta de Endereço**: Permite ler ou digitar um endereço (Ex: `A-01-01`). Mostra todos os produtos e lotes estocados ali com suas quantidades, SKU e datas de validade (destaca lotes vencidos em vermelho). Possui atalhos para Transferir ou Inventariar aquela posição.
  - **Consulta de Produto**: Permite ler o código de barras do produto ou buscar pelo nome/SKU. Mostra os dados cadastrais da embalagem e lista todos os endereços do armazém que possuem saldo daquele item, ordenados pela validade (FEFO), com opção rápida de mover o lote direto da consulta.

---

## 📊 Dados Fakes e Massa de Testes Gerada

Para garantir que os testes rodem de primeira sem precisar cadastrar dados manualmente pelo Filament Admin, os seeders foram aprimorados para gerar uma estrutura consistente.

### Lotes Ativos Previsíveis (Estoque Físico)
Os seguintes lotes foram seedados de forma fixa em endereços específicos para facilitar simulações de leitura:

| Produto | SKU | Endereço | Código do Lote (Scan) | Saldo Inicial |
| :--- | :--- | :--- | :--- | :--- |
| Fone Ouvido Bluetooth ANC | `TECH-EAR-ANC01` | `A-01-01` | `LOTE-FONE-01` | **50** |
| Teclado Mecanico RGB GPRO | `TECH-KEY-RGB09` | `A-01-02` | `LOTE-TECLADO-01` | **30** |
| Camiseta Algodao Pima Preta G | `MODA-TSH-PIMAP-G` | `B-02-01` | `LOTE-CAMISETA-01` | **80** |
| Cafeteira Espresso Italiana 6x | `HOME-CAF-ITA06` | `C-03-01` | `LOTE-CAFETEIRA-01` | **25** |
| Mouse Sem Fio Ergonomico | `TECH-MOU-ERGO` | `D-04-01` | `LOTE-MOUSE-01` | **45** |
| Calca Jeans Slim Masculina 42 | `MODA-JNS-SLIM-42` | `A-02-01` | `LOTE-CALCA-01` | **15** |

### Recebimentos (Para Testar Conferência)
- **10 Recebimentos Concluídos**: Status `concluido`. Lotes gerados e estoque somado ao produto.
- **4 Recebimentos Pendentes (Rascunho)**: Status `rascunho` (visíveis na tela de Conferência). Os códigos dos lotes associados a eles para teste seguem o padrão: `LOTE-RASC-{id}-{index}` (Exemplo: `LOTE-RASC-1-0`, `LOTE-RASC-1-1`).
- **3 Recebimentos Em Conferência**: Status `em_conferencia`. Códigos dos lotes seguem o padrão: `LOTE-CONF-{id}-{index}` (Exemplo: `LOTE-CONF-1-0`, `LOTE-CONF-1-1`).

### Pedidos (Para Testar Separação/Picking)
Gerados vários pedidos com status diferentes:
- **Pedidos Pendentes**: Prontos para separar (Ex: Pedido de 2 Fones `TECH-EAR-ANC01` e Pedido de 4 Camisetas `MODA-TSH-PIMAP-G`).
- **Pedidos Em Separação**: Pedido ativo em andamento (Ex: Teclado RGB com 1 unidade).
- **Pedidos Conferidos, Expedidos, Entregues e Cancelados**: Geram o histórico completo no sistema.

---

## 🚀 Como Executar e Validar no Projeto Principal

Se o outro chat/ambiente estiver rodando via Docker, peça para executar estes passos após mesclar os arquivos:

1. **Resetar e Alimentar o Banco de Dados**:
   Execute o comando abaixo no terminal para recriar as tabelas do zero e rodar os seeders atualizados:
   ```bash
   docker compose exec app php artisan migrate:fresh --seed
   ```

2. **Testar os Fluxos Mobile**:
   Acesse a URL [http://localhost:8088/app](http://localhost:8088/app) no navegador.

   - **Testando a Conferência**:
     - Vá em `/app/conferencia`, selecione a entrega da "Distribuidora Fenix 1".
     - Clique em **Scan**, mude para **Digitar / Simular**, digite `LOTE-RASC-1-0` e confirme. Veja a barra subir.
   
   - **Testando a Separação**:
     - Vá em `/app/separacao`, selecione o pedido de Fone Bluetooth (2 unidades).
     - O sistema indicará que você deve coletar 2 unidades no endereço `A-01-01`.
     - Clique em **Scan**, simule a leitura do código do lote `LOTE-FONE-01`. Veja a confirmação de coleta.
   
   - **Testando o Endereçamento**:
     - Vá em `/app/enderecamento`, busque pelo produto `Fone Ouvido` ou escaneie o código `TECH-EAR-ANC01`.
     - Você verá que ele está armazenado em `A-01-01` com o lote `LOTE-FONE-01` e saldo de 50 unidades.
     - Clique no link do endereço `A-01-01` para inspecionar a posição, ou use os atalhos de deep link para mover os itens.
