#!/usr/bin/env bash
# Instala MariaDB y aplica una configuración segura básica (Debian/Ubuntu)
# Variables opcionales:
#   DB_ROOT_PASS="clave"   -> define contraseña para root
#   DB_NAME="miapp" DB_USER="usuario" DB_PASS="clave"  -> crea BD y usuario
set -euo pipefail

if [[ $EUID -ne 0 ]]; then
  echo "Ejecuta como root: sudo $0" >&2
  exit 1
fi

echo "==> Instalando MariaDB..."
apt-get update -y
apt-get install -y mariadb-server mariadb-client

systemctl enable --now mariadb

echo "==> Aplicando configuración segura..."
mariadb <<SQL
DELETE FROM mysql.global_priv WHERE User='';
DROP DATABASE IF EXISTS test;
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
FLUSH PRIVILEGES;
SQL

if [[ -n "${DB_ROOT_PASS:-}" ]]; then
  mariadb -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${DB_ROOT_PASS}'; FLUSH PRIVILEGES;"
  echo "Contraseña de root establecida."
fi

if [[ -n "${DB_NAME:-}" && -n "${DB_USER:-}" && -n "${DB_PASS:-}" ]]; then
  mariadb <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL
  echo "BD '${DB_NAME}' y usuario '${DB_USER}' creados."
fi

echo "==> MariaDB instalado: $(mariadb --version)"
