<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Etiqueta de Envio - Pedido #{{ $pedido->id }}</title>
    <style>
        @page {
            size: 100mm 150mm;
            margin: 5mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1a202c;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            border: 2px solid #000;
            padding: 5px;
            height: 135mm;
            box-sizing: border-box;
            position: relative;
        }
        .header {
            border-bottom: 2px solid #000;
            text-align: center;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1px;
        }
        .header span {
            font-size: 9px;
            color: #4a5568;
            font-weight: bold;
        }
        .section {
            border-bottom: 1px dashed #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #718096;
            margin-bottom: 2px;
        }
        .destinatario-nome {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .grid {
            width: 100%;
            border-collapse: collapse;
        }
        .grid td {
            vertical-align: top;
            padding: 0;
        }
        .qr-section {
            text-align: center;
            padding-top: 10px;
            border-top: 2px solid #000;
            position: absolute;
            bottom: 5px;
            left: 5px;
            right: 5px;
        }
        .qr-table {
            width: 100%;
        }
        .qr-code-img {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto;
        }
        .order-meta {
            font-size: 10px;
            text-align: left;
        }
        .tracking-code {
            font-size: 14px;
            font-weight: bold;
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .barcode-img {
            width: 95%;
            height: 32px;
            display: block;
            margin-top: 4px;
            margin-bottom: 4px;
        }
        .badge {
            display: inline-block;
            background-color: #e2e8f0;
            padding: 2px 5px;
            font-weight: bold;
            font-size: 9px;
            border-radius: 3px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <h1>Nexus Logística</h1>
            <span>DOCUMENTO AUXILIAR DE EXPEDIÇÃO (ERP)</span>
        </div>

        <!-- DESTINATÁRIO -->
        <div class="section">
            <div class="section-title">Destinatário (Entrega)</div>
            <div class="destinatario-nome">{{ $pedido->cliente->nome }}</div>
            <div><strong>CNPJ:</strong> {{ $pedido->cliente->cnpj }}</div>
            <div><strong>Endereço:</strong> {{ $pedido->cliente->endereco ?? 'Não Informado' }}</div>
            <div><strong>Contato:</strong> {{ $pedido->cliente->telefone ?? 'N/A' }} | {{ $pedido->cliente->email ?? 'N/A' }}</div>
        </div>

        <!-- REMETENTE (ORIGEM) -->
        <div class="section">
            <div class="section-title">Remetente (CD Origem)</div>
            <div><strong>CD:</strong> {{ $pedido->centroDistribuicao->nome }} ({{ $pedido->centroDistribuicao->codigo_interno }})</div>
            <div><strong>Endereço:</strong> {{ $pedido->centroDistribuicao->endereco }}, {{ $pedido->centroDistribuicao->cidade }} - {{ $pedido->centroDistribuicao->estado }}</div>
        </div>

        <!-- PRODUTO -->
        <div class="section">
            <div class="section-title">Dados do Volume</div>
            <table class="grid">
                <tr>
                    <td>
                        <div><strong>Item:</strong> {{ $pedido->produto->nome }}</div>
                        <div><strong>SKU:</strong> {{ $pedido->produto->sku }}</div>
                        <div><strong>Qtd:</strong> {{ $pedido->quantidade }} un</div>
                        <div style="margin-top: 5px; background: #ffff00; display: inline-block; padding: 2px 4px; border: 1px solid #000; border-radius: 2px; font-weight: bold;">
                            ROTA: {{ $rotaColeta ?? 'SEM ENDEREÇO' }}
                        </div>
                    </td>
                    <td style="text-align: right; width: 40%;">
                        <div><strong>Peso Total:</strong> {{ number_format(($pedido->produto->peso ?? 0) * $pedido->quantidade, 2, ',', '.') }} Kg</div>
                        <div><strong>Cubagem:</strong> {{ ($pedido->produto->altura ?? 0) }}x{{ ($pedido->produto->largura ?? 0) }}x{{ ($pedido->produto->comprimento ?? 0) }} cm</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- TRANSPORTE & QR CODE -->
        <div class="qr-section">
            <table class="qr-table">
                <tr>
                    <td class="order-meta" style="width: 60%;">
                        <div><strong>Transportadora:</strong></div>
                        <div style="font-size: 12px; font-weight: bold;">{{ $pedido->transportadora ? $pedido->transportadora->nome : 'Aguardando Expedição' }}</div>
                        
                        <div style="margin-top: 8px;"><strong>Código de Rastreio:</strong></div>
                        <div class="tracking-code">{{ $pedido->codigo_rastreio ?? 'PENDENTE_RASTREIO' }}</div>
                        <img class="barcode-img" src="data:image/png;base64,{{ $barcode }}" alt="Barcode">
                        
                        <div style="margin-top: 6px;">
                            <span class="badge">Pedido #{{ $pedido->id }}</span>
                            <span class="badge" style="background-color: #edf2f7;">Vol: 1/1</span>
                        </div>
                    </td>
                    <td style="width: 40%; text-align: center;">
                        <img class="qr-code-img" src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code">
                        <div style="font-size: 8px; margin-top: 2px; font-weight: bold;">SCAN ME</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
