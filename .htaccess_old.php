RewriteEngine On
RewriteBase /

RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.php -f

RewriteRule ^producto/(.*)$  ./producto.php?id=$1 [L]
RewriteRule ^buscar/(.*)$  ./buscar.php?id=$1 [L,QSA]
RewriteRule ^acceso/(.*)$  ./acceso.php?id=$1 [L,QSA]
RewriteRule ^registro/(.*)$  ./registro.php?id=$1 [L,QSA]
RewriteRule ^carrito/(.*)$  ./cart.php?id=$1 [L,QSA]
RewriteRule ^deseos/(.*)$  ./wish.php?id=$1 [L,QSA]
RewriteRule ^finalizar/(.*)$  ./finalizar.php?id=$1 [L,QSA]
RewriteRule ^pago/(.*)$  ./pago.php?id=$1 [L,QSA]
RewriteRule ^recuperar/(.*)$  ./recuperar.php?id=$1 [L,QSA]

# php -- BEGIN cPanel-generated handler, do not edit
# Configure el paquete “ea-php73” como el lenguaje de programación predeterminado “PHP”.
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php73 .php .php7 .phtml
</IfModule>
# php -- END cPanel-generated handler, do not edit