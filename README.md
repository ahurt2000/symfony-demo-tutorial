# Proyecto demo. API Symfony para tutorial sencillo

En este  proyecto tutorial construimos la aplicación con el framework Symfony.

**Requisitos**: Tener instalado en tu sistema: git, docker y docker-compose

Comienza clonando el proyecto

```
git clone https://github.com/ahurt2000/demo-api-symfony.git
cd demo-api-symfony
```

Puedes desplazarte entre los pasos utilizando git para moverte a los commit correspondientes, cada paso tiene un tag

Indice

- Paso 1 Entorno: git checkout step-1
- Paso 2 Creación del proyecto: git checkout step-2

## Paso 1 (entorno)

Preparando el entorno de trabajo que necesitas. En este tutorial utilizarás docker para tener un entorno LAMP completo. Un contenedor docker expondrá un apache con un vhost que escuchara 

 - Configurando nombre del proyecto

 Debes editar el fichero .env y poner nombre del proyecto. Por defecto esta como "symfony-demo" pero puedes poner el que desees.

 ```
 COMPOSE_PROJECT_NAME=nombre-del-proyecto
 ```

Es importante que añadas una entrada en el fichero hosts de tu equipo. Para saber su localización usa este enlace [https://es.wikipedia.org/wiki/Archivo_hosts](https://es.wikipedia.org/wiki/Archivo_hosts)

127.0.0.1 nombre-del-proyecto.local

- Crear carpeta html

Es necesario que crees una carpeta de nombre html que contendrá el proyecto.

```
mkdir html
```

 - Compilar imágenes de docker

Este paso es el  que más tiempo toma, en dependencia de la potencia de tu pc/portátil. 

```
docker-compose build
```

No es estrictamente necesario sino se realiza el paso siguiente ejecutará el build de todas formas. Pero es recomendable acostumbrarse, si en adelante realizas un cambio en el fichero docker-compose.yml o los Dockerfile necesitarás recompilar con este comando.

 - Levantar contenedores

```
docker-compose up -d
```

Tras esto debes tener levantado 3 contenedores docker

Utiliza estos alias ya sea en tu sesión de la terminal. También puedes ponerlo en tu ~/.bashrc o ~/.bash_aliases 

```
source .env
alias symfony="docker exec -it -u www-data ${COMPOSE_PROJECT_NAME}-php symfony"
alias composer="docker exec -it -u www-data ${COMPOSE_PROJECT_NAME}-php composer"
```

Comprueba funcionamiento del installer de Symfony: *

```
symfony check:requirements
```

```
composer
```

Debes obtener una lista de comandos.

Composer es el gestor de paquetes que más se utiliza en PHP y Symfony. Si vienes de java, encontrarás composer es similar a Maven.

* En este tutorial no usamos el installer de symfony en su lugar usaremos composer. 


## Paso 2 Proyecto Symfony

En el proyecto trabajaremos con el codigo que ya esta en el repositorio, pero para fines didácticos mostraremos como crear un proyecto Symfony

- Crear proyecto symfony

Ejecuta el comando:

```
composer create-project symfony/website-skeleton .
```

Esto hará uso de composer para descargar y pre configurar todos los paquetes bases de Symfony.

Siempre podremos añadir nuevas dependencias con el mismo composer, por ejemplo:

```
composer require jajo/jsondb
```

Esto nos descarga el paquete https://github.com/donjajo/php-jsondb en nuestro proyecto en la carpeta vendor(html/vendor)


Al terminar debemos eliminar el contenido de la carpeta html, ya que utlizaremos el que descargas del repositorio en siguientes pasos.

```
rm -rf html; mkdir html
```