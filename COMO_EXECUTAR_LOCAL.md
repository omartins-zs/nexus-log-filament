# 📗 Como Executar (Ambiente Local)

Este documento orienta como rodar a aplicação **Nexus Log Filament** nativamente em sua máquina, assumindo que você possui um ambiente local como o **Laragon** ou similar (PHP 8.4+, MySQL nativo e Composer). A stack do projeto é a TALL (Tailwind, Alpine, Laravel 11, Livewire) em conjunto com o Filament v3.

---

## 1) Preparar o Ambiente

1. Copie o arquivo `.env.example` para `.env`:
   ```bash
   cp .env.example .env
   ```

2. Abra o arquivo `.env` e ative o **bloco LOCAL** de variáveis de ambiente. Configure a conexão de banco de dados para apontar para o seu MySQL nativo (por exemplo, na porta `3307` usada no Laragon):
   ```env
   # LOCAL (USADO NATIVAMENTE NO LARAGON/ARTISAN)
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=nexus_log_filament
   DB_USERNAME=root
   DB_PASSWORD=

   # Drivers locais (sem Redis)
   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```

---

## 2) Instalar Dependências

No terminal, instale os pacotes backend (PHP) e frontend (Node.js):

1. **Dependências PHP (Laravel/Filament)**:
   ```bash
   composer install
   ```

2. **Dependências Node (Tailwind/Vite)**:
   ```bash
   npm install
   ```

3. **Geração de Chave e Banco de Dados**:
   ```bash
   # Gera a application key
   php artisan key:generate

   # Roda as migrations e popula os seeders logísticos
   php artisan migrate --seed

   # Publica os assets visuais do Filament
   php artisan filament:assets
   ```

---

## 3) Rodar a Aplicação

Se você estiver utilizando Laragon com domínios virtuais automáticos (ex: `nexus-log-filament.test`), a aplicação já estará servida automaticamente no Apache/Nginx local. Caso contrário, você pode iniciar o servidor embutido do PHP:

```bash
php artisan serve
```

---

## 5) Frontend (Vite & Tailwind CSS)

Para compilar os arquivos de estilo CSS, Tailwind e Livewire em tempo real durante o desenvolvimento, mantenha o seguinte comando rodando em uma janela de terminal paralela:

```bash
npm run dev
```

---

## 4) Filas / Workers (Se existirem processamentos paralelos)

Se a aplicação estiver agendando envios de e-mails, processos assíncronos ou geração em massa de etiquetas logísticas usando o driver local (`QUEUE_CONNECTION=database`), execute um worker em um novo terminal:

```bash
php artisan queue:work
```

---

## 6) Acessos e URLs

Com a aplicação servida, você pode acessar:

- **App Local**: [http://localhost:8000](http://localhost:8000) (ou via virtual host `http://nexus-log-filament.test` no Laragon)
- **Painel Administrativo Filament**: [http://localhost:8000/admin](http://localhost:8000/admin) (Logins e senhas estarão disponíveis a partir dos usuários injetados pelo Seeder)
