test:
	docker-compose exec -w /app2 php ./bin/phpunit

up:
	docker-compose up -d

down:
	docker-compose down