alias dc="docker-compose -f provisioning/development/docker-compose.yml -p albion"
alias rcli="dc exec php-fpm php bin/cli.php"
alias rcomposer="dc exec php-fpm composer"