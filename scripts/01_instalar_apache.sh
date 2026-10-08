#!/usr/bin/env bash
# Instala y habilita Apache2 (Debian/Ubuntu)
set -euo pipefail

if [[ $EUID -ne 0 ]]; then
  echo "Ejecuta como root: sudo $0" >&2
  exit 1
fi

echo "==> Actualizando paquetes..."
apt-get update -y

echo "==> Instalando Apache..."
apt-get install -y apache2

echo "==> Habilitando mod_rewrite y arrancando el servicio..."
a2enmod rewrite
systemctl enable --now apache2
systemctl restart apache2

# Firewall (solo si ufw está instalado)
if command -v ufw >/dev/null 2>&1; then
  ufw allow 'Apache Full' || true
fi

echo "==> Apache instalado: $(apache2 -v | head -n1)"
echo "Prueba en: http://localhost"
