# 📦 Fluxo de Status do Pedido (Nexus Log)

Este documento descreve o ciclo de vida de um Pedido dentro do sistema, desde a sua criação até a entrega final ao cliente.

## 🔄 Ordem Cronológica dos Status

### 1. `Pendente` (Pendente)
- **Quando ocorre:** Assim que o pedido é criado no sistema (seja via importação de API, Excel ou cadastro manual).
- **O que significa:** O pedido entrou no armazém (CD), mas ninguém começou a trabalhar nele ainda.

### 2. `Em Separação` (Em Separação)
- **Quando ocorre:** Quando o operador de armazém inicia a coleta dos itens nas prateleiras (Picking).
- **O que significa:** Os produtos estão sendo separados fisicamente no estoque.

### 3. `Conferido` (Conferido)
- **Quando ocorre:** Após o operador bipar todos os itens daquele pedido para garantir que não falta nada e não há erro (Packing).
- **O que significa:** O pedido já está embalado e garantido que os itens corretos estão na caixa.

### 4. `Aguardando Expedição` (Aguardando Expedição)
- **Quando ocorre:** Após a caixa ser fechada, etiquetada e roteirizada (como na Ação em Massa que criamos).
- **O que significa:** A caixa está na doca de saída, apenas esperando o caminhão da Transportadora encostar para levar.

### 5. `Expedido` (Expedido)
- **Quando ocorre:** Quando o pedido é de fato carregado no caminhão da transportadora (Bipagem de Expedição).
- **O que significa:** O pacote saiu fisicamente do nosso Centro de Distribuição e já está em posse da transportadora.

### 6. `Entregue` (Entregue)
- **Quando ocorre:** Quando a transportadora confirma via integração/baixa que o cliente assinou o recebimento.
- **O que significa:** Fim da jornada. Pedido finalizado com sucesso.

---

## ❌ Status de Exceção

### `Cancelado` (Cancelado)
- **Quando ocorre:** Se o cliente desistir da compra ou houver falta de estoque crítica.
- **O que significa:** O fluxo logístico foi abortado e os itens, se já separados, devem voltar para a prateleira.

---

## 💡 Para que serve o "Processar Roteirização (Fila)"?

O botão **"Processar Roteirização (Fila)"** que criei serve para **simular** a automação da transição do status `Pendente/Conferido` para `Aguardando Expedição`. 

Em um cenário logístico real (WMS/ERP), quando você tem 1.000 pedidos para despachar, o sistema precisa:
1. Calcular o peso total de cada pedido.
2. Bater na API dos Correios, Jadlog, Loggi, etc.
3. Escolher a transportadora mais barata.
4. Gerar o Código de Rastreio.

Se o sistema tentasse fazer isso na mesma hora em que você clica no botão, sua tela ficaria travada por 10 minutos (e provavelmente daria erro de *Timeout*).
A **Fila (Queue)** pega esses 1.000 pedidos e coloca em uma lista de espera invisível. O robô em background (`php artisan queue:work`) vai lendo essa lista e processando um por um em segundo plano, enquanto você continua usando o painel do Filament livremente!
