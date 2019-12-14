## Deploying
Docker required. 

The first step is to copy .env.example to .env and pass the infura parameters there

```$xslt
cd .docker
docker-compose up -d
docker exec -it crypto-php-fpm bash
php artisan key:generate
php artisan migrate
php artisan infura:listen
```

Then you will have an access to the application on http://localhost:8080/

### Endpoints

http://localhost:8080/wallets 
get all wallets
http://localhost:8080/wallets/create
add wallet
