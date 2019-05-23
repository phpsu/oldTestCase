docker-compose up -d
docker-compose exec local bash -c 'vendor/bin/phpunit-watcher watch tests --color=always --order-by=defects,random'
