docker-compose up -d
docker-compose exec -u1000 local bash -c 'vendor/bin/phpunit-watcher watch tests --color=always --order-by=defects,random'
