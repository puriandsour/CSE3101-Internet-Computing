#!/bin/bash
set -e

# Physically remove any conflicting MPM modules at the moment of startup
echo "Cleaning up Apache MPM modules..."
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf

# Force enable prefork (required for PHP-Apache)
a2enmod mpm_prefork

# Execute the original Apache startup command
echo "Starting Apache..."
exec apache2-foreground
