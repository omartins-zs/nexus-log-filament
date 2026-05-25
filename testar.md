# 📱 Como testar a bipagem usando seu Celular

Existem duas maneiras de você testar a leitura de códigos usando o seu celular no sistema que construímos:

## Opção 1: Transformar seu celular em um Leitor de Mão (Recomendado)
Sabe aquelas pistolas de código de barras USB de supermercado? Elas nada mais são do que um "teclado" que digita muito rápido e aperta o Enter no final. Você pode usar seu celular exatamente assim!

1. Baixe o aplicativo **"Barcode to PC"** no seu celular (Android ou iPhone) e baixe o programa servidor deles no seu Windows.
2. Abra a nossa página de **"Bipagem / Armazém"** no navegador do seu computador.
3. No computador, clique no campo grande "BIPE O CÓDIGO AQUI..." para que ele fique piscando (com o foco).
4. No aplicativo do celular, aponte a câmera para o QR Code do nosso PDF da etiqueta.
5. **Mágica:** O celular vai enviar o código para o seu computador via Wi-Fi, vai colar no campo e apertar Enter automático. O sistema vai bipar e fazer a baixa do pedido!

## Opção 2: Acessar o sistema pelo celular
Se você quiser usar o próprio celular como o "painel principal", você precisa estar na mesma rede Wi-Fi do seu computador:

1. No Windows, descubra o seu IP local (abra o CMD e digite `ipconfig`, anote o IPv4, ex: `192.168.1.15`).
2. Como você está usando o Docker/Laragon na porta 8000, pegue seu celular, abra o Google Chrome ou Safari e acesse: `http://192.168.1.15:8000/admin`
3. Faça o login com o seu usuário e vá até a página de Bipagem.
4. **Como bipar pelo celular:** Alguns teclados modernos (como o da Samsung ou o do iPhone) possuem um ícone de **"Escanear Texto"** ou **"QR Code"** direto no teclado. Basta clicar no campo de input, abrir essa ferramenta nativa do teclado e apontar para a etiqueta do monitor. Ele preencherá o texto e você só precisará apertar "Ir/Enter" no teclado!
