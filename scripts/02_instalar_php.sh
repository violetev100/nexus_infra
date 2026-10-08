#!/usr/bin/env bash
# Instala PHP + módulos comunes para Apache (Debian/Ubuntu)
set -euo pipefail

if [[ $EUID -ne 0 ]]; then
  echo "Ejecuta como root: sudo $0" >&2
  exit 1
fi

if ! command -v apache2 >/dev/null 2>&1; then
  echo "Apache no está instalado. Ejecuta primero 01_instalar_apache.sh" >&2
  exit 1
fi

echo "==> Instalando PHP y extensiones..."
apt-get update -y
apt-get install -y php libapache2-mod-php php-cli php-mysql php-curl \
  php-gd php-mbstring php-xml php-zip php-intl

# Archivo de prueba
echo "<?php phpinfo();" > /var/www/html/info.php

systemctl restart apache2

echo "==> PHP instalado: $(php -v | head -n1)"
echo "Prueba en: http://localhost/info.php"
echo "IMPORTANTE: borra info.php en producción: sudo rm /var/www/html/info.php"
