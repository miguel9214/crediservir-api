# Crediservir API

Crediservir API es una aplicación diseñada para gestionar la compra de boletos para eventos. Permite a los usuarios seleccionar eventos, elegir asistentes y aplicar códigos de descuento.

## Requisitos

-   PHP 7.3 o superior
-   Composer
-   MySQL
-   Servidor web (se recomienda Apache)
-   Laravel 8.x

## Instalación

Para ejecutar la API, es necesario contar con un servicio de Apache y MySQL. Se recomienda utilizar la aplicación [Laragon](https://laragon.org/), que incluye estos servicios.

### Pasos para la instalación

1. **Clonar el repositorio**:
    ```bash
    git clone https://github.com/miguel9214/crediservir-api.git
    ```

### instalar composer

2. **comando**:
    ```bash
    composer install
    ```

### Configurar el entorno

3. **comando**:
    ```bash
    Copia el archivo .env.example a .env
    Abre el archivo .env y configura los detalles de conexión a la base de datos:
    env
    Copiar código
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=crediservir_api
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña
    ```

### instalar secret key

4. **comando**:
    ```bash
    php artisan jwt:secret
    ```

### iniciar migraciones y seeders

5. **comando**:

    ```bash
    php artisan migrate --seed
    ```
### Comprobar el funcionamiento de la API

6. **comando**:

    ```bash
    http://crediservir-api.test/
    ```
