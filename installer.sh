#!/bin/bash

# Update system
echo "Updating system packages..."
sudo apt-get update
sudo apt-get upgrade -y

# Install required dependencies
echo "Installing dependencies..."
sudo apt-get install -y \
    php8.1 \
    php8.1-cli \
    php8.1-common \
    php8.1-mysql \
    php8.1-zip \
    php8.1-gd \
    php8.1-mbstring \
    php8.1-curl \
    php8.1-xml \
    php8.1-bcmath \
    php8.1-intl \
    php8.1-redis \
    composer \
    nginx \
    mysql-server \
    git \
    unzip \
    redis-server \
    nodejs \
    npm

# Configure MySQL
echo "Configuring MySQL..."
sudo mysql_secure_installation

# Create database
echo "Creating database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS factory_management;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'factory_user'@'localhost' IDENTIFIED BY 'factory_password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON factory_management.* TO 'factory_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Clone and setup Laravel project
echo "Setting up Laravel project..."
git clone https://github.com/yourusername/factory-management.git /var/www/factory-management
cd /var/www/factory-management

# Install Composer dependencies
composer install

# Copy environment file and generate key
cp .env.example .env
php artisan key:generate

# Configure environment file
sed -i 's/DB_DATABASE=laravel/DB_DATABASE=factory_management/' .env
sed -i 's/DB_USERNAME=root/DB_USERNAME=factory_user/' .env
sed -i 's/DB_PASSWORD=/DB_PASSWORD=factory_password/' .env

# Set permissions
sudo chown -R www-data:www-data /var/www/factory-management
sudo chmod -R 755 /var/www/factory-management
sudo chmod -R 777 /var/www/factory-management/storage

# Configure Nginx
echo "Configuring Nginx..."
sudo bash -c 'cat > /etc/nginx/sites-available/factory-management << EOL
server {
    listen 80;
    server_name factory.local;
    root /var/www/factory-management/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOL'

# Enable the site
sudo ln -s /etc/nginx/sites-available/factory-management /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Restart Nginx
sudo systemctl restart nginx

# Run migrations and seed database
php artisan migrate --seed

# Install Node.js dependencies and compile assets
npm install
npm run build

echo "Installation complete!"
echo "Please add 'factory.local' to your /etc/hosts file:"
echo "127.0.0.1 factory.local"
echo "Then access the application at http://factory.local"