# How to run this project?

1. Install __docker__ and __docker-compose__
2.  To build the container you should execute the next command:
`make build` 
3. To run the container you should execute 
`make run`
4. To do both things step by step in one command:
`make runbuild` 
5. Then we should manually get into the backend image, running:
`docker exec -it taxi-manager_backend_1 bash`
6. Inside the bash environment it's time to apply fixtures to get started with prepared data. There is a command list:
`php bin/console doctrine:database:create --if-not-exists`
`php bin/console doctrine:migrations:migrate --no-interaction`
`php bin/console doctrine:fixtures:load --no-interaction`
7. Eventually open http://localhost:8080/ and start working with the website.
