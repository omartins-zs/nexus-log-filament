# Como Testar o Nexus Log Filament

## 📱 WMS Mobile PWA Concluído!

O sistema mobile para o chão de fábrica foi totalmente implementado, separando a operação logística (PWA) do planejamento e controle (PCP/Filament).

### 🚀 O que foi feito?
Toda a infraestrutura do PWA e as 5 funcionalidades principais solicitadas já estão rodando com interface nativa e permissões por usuário!

### 1️⃣ Controle de Acesso e PCP (Filament)
No painel do administrador (`/admin`), você agora controla exatamente o que cada operador pode fazer no celular:
- **Cadastro de Embalagens**: Tipos de recipientes de tinta (lata, balde, tambor) adicionados.
- **Produtos adaptados para Tintas**: O cadastro de produtos agora inclui Cor, Linha (Premium/Econômica), Tipo de Tinta e Embalagem.
- **Permissões Mobile**: Na edição do Usuário, há um novo campo com checkboxes para escolher se o operador terá acesso à Conferência, Inventário, Separação, Transferência e/ou Endereçamento.

### 2️⃣ O Aplicativo Mobile PWA (`/app`)
Acessando pelo celular, o layout é 100% responsivo, focado em toques rápidos e Dark Mode para economia de bateria e redução de brilho no galpão. O app conta com:
- **Hub Central**: Uma tela inicial com botões gigantes e coloridos apenas com as permissões que o operador logado tem.
- **Scanner Inteligente**: O botão flutuante de Scanner (no menu inferior) será ativado em todas as telas para leitura de código de barras ou QR Code com a câmera traseira do celular usando `html5-qrcode`.

### 3️⃣ As 5 Funcionalidades Operacionais
Implementamos os componentes interativos do Livewire para:
- **Conferência Física de Recebimento (`/app/conferencia`)**: Lista os recebimentos pendentes. O operador escaneia as notas ou os produtos e vai checando os itens um a um até finalizar a conferência.
- **Inventário (`/app/inventario`)**: Permite buscar ou escanear um endereço (ex: C-01-01) e ajustar a contagem de estoque real contra o esperado, produto por produto.
- **Separação e Expedição (`/app/separacao`)**: Com base no FEFO (Primeiro que Vence, Primeiro que Sai), o app cria a Rota de Coleta. O operador escaneia o produto no endereço para confirmar a retirada do lote correto.
- **Transferência de Produtos (`/app/transferencia`)**: Um fluxo em 4 passos rápidos: Escanear Origem → Selecionar Produto → Escanear Destino → Confirmar Transferência.
- **Endereçamento de Armazenagem (`/app/enderecamento`)**: Consulta rápida de onde está cada produto, os saldos e validades, escaneando o código de barras de uma prateleira ou produto.

### 🛠️ Como Testar as Sessões e Acessos Simultâneos
Você pode testar os **dois sistemas ao mesmo tempo, no mesmo navegador**, pois as sessões foram separadas com sucesso (guardião `web` para admin e guardião `mobile` para o app).

1. **Acesse o PCP (Painel Administrativo):**
   - **URL:** `http://localhost:8088/admin`
   - **Login Padrão:** O e-mail do seu usuário administrador (ex: o usuário que você costuma usar para gerenciar o sistema). Se usou os seeders, utilize o login padrão do seeder ou cadastre um novo.
   - **O que fazer aqui:** Vá na aba "Usuários", crie ou edite um operador e marque as "Permissões Mobile" (ex: marcar apenas "Inventário" e "Separação"). Deixe esta aba aberta.

2. **Acesse o PWA Mobile (Chão de Fábrica):**
   - **URL:** `http://localhost:8088/app`
   - **Login:** Use o e-mail e a senha do **Operador** que você acabou de configurar no passo anterior.
   - **O que vai acontecer:** Você verá o Hub Central, mas ele só mostrará os botões "Inventário" e "Separação".

3. **Validação das Sessões Independentes:**
   - Volte para a aba do `/admin` e dê F5. Você **continuará logado como Administrador**.
   - Volte para a aba do `/app`, clique no botão **"Mais"** (na barra inferior) e depois em **"Sair"**.
   - Resultado: O Operador será deslogado do PWA, mas se você voltar na aba do `/admin` e atualizar, o seu Administrador continuará logado!

> [!TIP]
> **Instalando o App**: Se acessar pelo Google Chrome no celular (ex: `http://192.168.1.X:8088/app`), você verá um prompt para "Adicionar à Tela Inicial". Ao fazer isso, o sistema se comporta como um App nativo, abrindo em tela cheia sem a barra de endereço!
