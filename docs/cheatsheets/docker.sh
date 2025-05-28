# Installing and Checking Docker

docker --version  # Check the Docker version
docker build -t <image-name> .  # Build a Docker image from a Dockerfile in the current directory
docker build -f <path-to-Dockerfile> -t <image-name> .  # Build a Docker image with a specific Dockerfile
docker run <image-name>  # Run a Docker container from an image
docker run -it <image-name>  # Run a container in interactive mode with a TTY
docker run -d <image-name>  # Run a container in detached mode
docker run -p <host-port>:<container-port> <image-name>  # Map host port to container port
docker run --name <container-name> <image-name>  # Run a container with a custom name
docker run --rm <image-name>  # Run a container and automatically remove it after it stops

# Managing Containers

docker start <container-name>  # Start a stopped container
docker stop <container-name>  # Stop a running container
docker restart <container-name>  # Restart a running container
docker rm <container-name>  # Remove a stopped container
docker exec -it <container-name> <command>  # Run a command inside a running container
docker ps  # List all running containers
docker ps -a  # List all containers, including stopped ones

# Logs and Monitoring

docker logs <container-name>  # View the logs of a container
docker logs -f <container-name>  # Follow the logs of a container

# Managing Images

docker images  # List all Docker images
docker pull <image-name>  # Download an image from a Docker registry (e.g., Docker Hub)
docker push <image-name>  # Push an image to a Docker registry
docker rmi <image-name>  # Remove a Docker image
docker tag <image-id> <repository>/<image-name>:<tag>  # Tag an image for pushing to a registry

# Networking

docker network ls  # List all Docker networks
docker network create <network-name>  # Create a new Docker network
docker network rm <network-name>  # Remove a Docker network
docker network connect <network-name> <container-name>  # Connect a container to a network
docker network disconnect <network-name> <container-name>  # Disconnect a container from a network

# Volumes

docker volume ls  # List all Docker volumes
docker volume create <volume-name>  # Create a new Docker volume
docker volume rm <volume-name>  # Remove a Docker volume

# System Cleanup

docker system prune  # Remove all stopped containers, unused networks, images, and volumes

# Docker Compose

docker-compose up  # Start services defined in a `docker-compose.yml` file
docker-compose down  # Stop and remove containers, networks, and volumes created by `docker-compose`
docker-compose build  # Build or rebuild services defined in a `docker-compose.yml` file
docker-compose logs  # View logs from services managed by `docker-compose`
docker-compose ps  # List services managed by `docker-compose`
