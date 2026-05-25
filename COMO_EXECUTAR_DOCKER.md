# 📘 Como Executar (Ambiente Docker)

Este documento orienta como rodar a aplicação **Nexus Log Filament** (Stack TALL: Laravel 11, Livewire, Filament v3, Tailwind) utilizando a infraestrutura Docker de alta performance já configurada no projeto.

---

## 1) Preparar o Ambiente

Primeiro, certifique-se de que o arquivo de configuração de ambiente existe e está devidamente configurado para o uso do Docker.

1. Copie o arquivo de exemplo:
   ```bash
   cp .env.example .env
   ```

2. Abra o arquivo `.env` gerado e certifique-se de **ativar o bloco Docker** e desativar o local, se necessário. Suas conexões de banco e cache devem apontar para os serviços Docker internos:
   ```env
   # DOCKER (USADO VIA DOCKER COMPOSE INTERNAMENTE)
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=nexus_log_filament
   DB_USERNAME=nexus_user
   DB_PASSWORD=nexus_pass

   # REDIS PARA CACHE E SESSÃO
   SESSION_DRIVER=redis
   CACHE_STORE=redis
   QUEUE_CONNECTION=redis
   REDIS_HOST=redis
   ```

---

## 2) Subir os Containers

Na raiz do projeto, construa e inicie os containers em segundo plano. O Docker cuidará de instalar o PHP 8.4, Nginx, MySQL e Redis nativamente.

```bash
docker compose up -d --build
```

> **Aviso**: Na primeira execução, o build pode demorar alguns minutos para baixar as imagens e compilar as extensões nativas do PHP (como OPcache e Redis).

---

## 3) Inicialização (Laravel + Filament)

Como a aplicação é baseada em Laravel, você precisará instalar as dependências do Composer e inicializar o banco de dados. Tudo isso deve ser executado **dentro do container da aplicação**.

1. **Instalar Dependências PHP**:
   ```bash
   docker compose exec app composer install
   ```

2. **Gerar a Chave da Aplicação** (Apenas na primeira vez):
   ```bash
   docker compose exec app php artisan key:generate
   ```

3. **Migrar e Popular o Banco de Dados**:
   ```bash
   docker compose exec app php artisan migrate --seed
   ```
   *(Isso criará a estrutura do ERP Logístico e populará com dados de teste para Clientes, Produtos, Pedidos, etc.)*

4. **Publicar os Assets do Filament** (Para garantir que a interface administrativa carregue corretamente):
   ```bash
   docker compose exec app php artisan filament:assets
   ```

---

## 4) Acessos

Com os containers rodando e a inicialização concluída, você pode acessar os serviços mapeados para a sua máquina (Host):

- **Aplicação Web (Nginx)**: [http://localhost:8088](http://localhost:8088)
- **Painel Administrativo Filament**: [http://localhost:8088/admin](http://localhost:8088/admin) (Acesse usando os usuários gerados pelo Seeder)
- **Banco de Dados Externo (MySQL)**: Porta `3318` (Útil se quiser usar DBeaver/HeidiSQL, credenciais: `nexus_user` / `nexus_pass`)
- **Redis Externo**: Porta `6389`

---

## 5) Logs e Monitoramento

Para acompanhar os logs da aplicação, workers ou do banco de dados em tempo real:

```bash
# Ver os logs de todos os serviços (acompanhamento contínuo)
docker compose logs -f

# Ver os logs de um serviço específico (ex: app / db / nginx / redis)
docker compose logs -f app
```
