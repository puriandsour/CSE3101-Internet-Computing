FROM php:8.2-apache

# 1. Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# 2. Enable Apache rewrite module
RUN a2enmod rewrite

# 3. Set working directory
WORKDIR /var/www/html

# 4. Copy application files
COPY . .

# 5. Set DocumentRoot to your school_app folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/school_app|g' \
    /etc/apache2/sites-available/000-default.conf

# 6. Allow .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' \
    /etc/apache2/apache2.conf

# 7. Set Permissions
RUN chown -R www-data:www-data /var/www/html

# 8. PREPARE ENTRYPOINT: Copy the script and make it executable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# 9. Expose port 80 for Railway
EXPOSE 80

# 10. Start using the entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
