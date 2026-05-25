#!/bin/sh

# Otimização Focada: Apenas limpa caches quando o container reinicia (cold start limpo)
# E só cacheados se estivermos rodando em Produção pura (APP_ENV=production)
echo "[Start-App] Verificando e limpando oxidação de caches antigos..."
php artisan optimize:clear

# Não recriamos cache se for APP_ENV=local para preservar Livewire e Vite HMR
if [ "$APP_ENV" = "production" ]; then
    echo "[Start-App] Ambiente Production detectado. Gerando novos Caches..."
    php artisan config:cache
    php artisan event:cache
    php artisan route:cache
    php artisan view:cache
fi

# Forçar permissões de storage apenas uma vez no boot
chmod -R 775 /var/www/storage /var/www/bootstrap/cache || true
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache || true

echo "[Start-App] Tudo pronto! Iniciando PHP-FPM..."
# Substitui a execução deste script pelo binário FPM (garante recebimento de sinais SIGTERM corretos)
exec php-fpm
