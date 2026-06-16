# Acessos de Teste (Seeders)

Os dados abaixo foram gerados automaticamente pelos Seeders da aplicação e podem ser utilizados para acessar o painel administrativo (Filament) e testar o sistema.

## 👤 Painel Administrativo (PCP - Filament)

- **URL de Acesso**: `/admin` (Ex: `http://localhost:8088/admin` no Docker ou `http://localhost:8000/admin` localmente)
- **E-mail**: `admin@nexus.com`
- **Senha**: `password`
- **Nome do Usuário**: Operador Nexus
- **Função**: Gestão de produtos, controle de acessos, inventários gerais e administração logística.

---

## 📱 App Mobile PWA (Chão de Fábrica)

- **URL de Acesso**: `/app` (Ex: `http://localhost:8088/app` no Docker ou `http://localhost:8000/app` localmente)
- **Login Padrão**: Você pode usar o mesmo `admin@nexus.com` e `password` (desde que tenha marcado as 'Permissões Mobile' no painel admin para ele).
- **Testes com 2 Usuários Simultâneos**: 
  Como as sessões são separadas por guardiões de autenticação, você pode:
  1. Logar no `/admin` com `admin@nexus.com` (senha: `password`).
  2. Abrir `/app` na mesma janela do navegador (ou em uma aba anônima) e logar com o operador: `conferente@nexus.com` (senha: `password`).
  3. Ambos funcionarão simultaneamente!

---

*Nota: Todos os outros dados (Clientes, Centros de Distribuição, Produtos, Transportadoras e Pedidos) foram populados no banco de dados automaticamente para facilitar a simulação de expedição logística e geração de relatórios.*
