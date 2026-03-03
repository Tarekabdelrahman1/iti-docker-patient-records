# Orchestrating Patient Records with Docker Compose 🏥

<img width="1024" height="565" alt="image" src="https://github.com/user-attachments/assets/61872a5c-983d-44f6-9e1e-e06365beea19" />



## 📌 Project Overview
This project is a containerized **LAMP Stack** application designed for managing patient medical records. It was developed as part of the **ITI (Information Technology Institute)** training to demonstrate proficiency in Docker, DevOps practices, and web orchestration.

## 🏗️ Architecture & Services
The application is orchestrated using **Docker Compose** with the following services:

* **Web Server (`patient_app`)**: PHP 7.4 + Apache, responsible for serving the UI and backend logic.
* **Database (`patient_mysql`)**: MySQL 5.7, storing patient records with persistent volumes.
* **Infrastructure Features**: 
    * **Healthchecks**: The Web service waits for the MySQL service to be "Healthy" before starting.
    * **Resource Limits**: Capped at 0.5 CPU and 512MB RAM for stability.
    * **Environment-Driven**: Uses `.env` files for secure credential management.

## 📂 Project Structure
```text
patient_system/
├── www/                 # PHP Source Code (index.php, fetch_patients.php, etc.)
├── sql/                 # Database initialization (init.sql)
├── .env                 # Environment variables (DB credentials)
├── Dockerfile           # PHP image customization (mysqli extension)
└── docker-compose.yml   # Multi-container orchestration
```

# 🛠️ Guided Lab: Infrastructure & Deployment
## 1. Environment Configuration (.env)
To follow DevOps best practices, we separate our secrets from our code. Create a .env file in the root directory:

```text
# Database Credentials
DB_ROOT_PASSWORD=1234
DB_NAME=patient_db
# Web Server Configuration
WEB_PORT=8080
```
## 2. The Custom Docker Image (Dockerfile)
We don't use a standard image. We build a custom one to ensure the PHP environment can communicate with MySQL.

**Dockerfile**
```text
# Use the official PHP-Apache base image
FROM php:7.4-apache

# Install and enable the mysqli extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Security: Hide PHP version from headers
RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/security.ini
```

## 3. Multi-Container Orchestration (docker-compose.yml)
This file defines the entire stack. It handles the networking, volumes for data persistence, and the Healthcheck logic.

```YAML
version: '3.8'
services:
  db:
    image: mysql:5.7
    container_name: patient_mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_NAME}
    volumes:
      - db_data:/var/lib/mysql
      - ./sql/init.sql:/docker-entrypoint-initdb.d/init.sql
    healthcheck:
      test: ["CMD", "mysqladmin" ,"ping", "-h", "localhost", "-u", "root", "-p${DB_ROOT_PASSWORD}"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - patient_net

  web:
    build: .
    container_name: patient_app
    ports:
      - "${WEB_PORT}:80"
    volumes:
      - ./www:/var/www/html
    depends_on:
      db:
        condition: service_healthy
    deploy:
      resources:
        limits:
          cpus: '0.50'
          memory: 512M
    networks:
      - patient_net

networks:
  patient_net:

volumes:
  db_data:
```
## 4. Deployment Steps
Once your files are ready, follow these commands in your Linux terminal:

### A. Build and Start the Stack
This command builds your custom image and starts the containers in the background (-d).

```Bash
docker compose up -d --build
```
### B. Verify Health Status
Wait a few seconds for the MySQL container to initialize. The patient_app will stay in a "Starting" state until the DB is healthy.

```Bash
docker ps
```
C. Access the Application
Once both containers are running, open your browser and navigate to:
```text
http://localhost:8080
```
<img width="1715" height="876" alt="image" src="https://github.com/user-attachments/assets/9dc5ed8b-3d3a-4439-bcec-746721e0fbc1" />
