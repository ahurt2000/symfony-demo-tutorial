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
- Paso 3 Primeros pasos: git checkout step-3
- Paso 4 Implementar operaciones: git checkout step-4

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

## Paso 3 Primeros pasos

A partir de este punto simpre trabajaremos en la carpeta "html" que es la de nuestro proyecto.

```
cd html
```

En este punto si has seguido las instrucciones debe poder ver la página de Bienvenida de Symfony en http://nombre-del-proyecto.local, por ejemplo si no modificaste el nombre del proyecto en el Paso 1 y añadiste a hosts la entrada correcta, deberias poder ver http://symfony-demo.local.local

Es conveniente que explores un poco la estructura de un proyecto Symfony. 

```
├── bin/                -> útiles ejecutables, el comando console y phpunit
├── config/             -> configuración del proyecto 
├── public/             -> es el la capeta raíz de la web (documentroot de apache) por index.php entran de todas las peticiones php.
├── src/                -> el código
├── ...                 -> otras carpetas que dependen del proyecto y son no importantes ahora.
├── var/                -> cache, logs, etc
├── vendor/             -> dependencias externas (paquetes que instalarás con composer generalmente)
├── .env                -> configuraciones dependientes del entorno (parámetros)
├── ...                 -> otros ficheros no importantes ahora.
├── symfony.lock
├── composer.json       -> ficheros de composer para la gestion de paquetes
└── composer.lock 
```

Dentro de la carpeta bin encontramos un ejecutable proporcioando por Symonfy para ayudar 

```
bin/console
```

Ahora podemos crear nuestro primer controlador ayudados por este comando.

- Primer Controlador

```
bin/console make:controller
```

Se lanza un wizard que pedirá el nombre del controlador que quieres crear, prueba Category. Automaticamente se crean dos ficheros. Uno es el controlador *src/Controller/CategoryController.php* (Los controladores deben terminar su nombre con Controller, Symfony lo añade por ti). El otro es una plantilla de Twig* con el código de generación del html que puede ver en: http://nombre-del-proyecto.local/category

*Twig* es un motor de plantillas para php. Si vienes de java es similar a thymeleaf

No lo usaremos porque crearemos una API, nuestras salidas serán respuestas json. 

Que revises la documentación oficial y si puedes leas el libro https://symfony.com/doc/6.2/the-fast-track/en/index.html. (La versión online es gratis)

## Paso 4 Implementar operaciones

Ahora que ya tenemos un controlador y una operación de negocio ficticia, vamos a modificarla para añadir las operaciones reales que consumirá nuestra pantalla. Deberemos añadir una operación para listar, una para actualizar, una para guardar y una para borrar. No vamos a trabajar directamente con datos simples, sino que usaremos objetos para recibir información y para enviar información.

Estos objetos típicamente se denominan DTO (Data Transfer Object) y nos sirven justamente para encapsular información que queremos transportar. En realidad no son más que clases sencillas con propiedades que encapsulan los datos. En versiones anteriores de PHP estas clases se construían con las propiedades, y sus métodos "setters" y "getters", pero en PHP 8.x aprovechamos la promoción de propiedades en el constructor [constructor property promotion](https://www.php.net/manual/en/language.oop5.decon.php#language.oop5.decon.constructor.promotion). 

Además de declarar las únicas propiedades el objeto como *readonly* para de esta forma hacer estos objetos inmutables. 

**las instrucciones asumen que que estas siempre en la carpeta del proyecto(html)**

```
mkdir -p src/Model
```

Dentro creamos la clase CategoryDto en el fichero *src/Model/CategoryDto.php*

```PHP
<?php

namespace App\Model;

final class CategoryDto 
{
    public function __construct(public readonly int $id, public readonly string $name) {

    }   
}
```

A continuación modificaremos el controlador para que utilice el DTO. 
Modifica la clase *CategoryController* en el fichero *src/Category/CategoryController.php* de forma que quede así:

```PHP
<?php

namespace App\Controller;

use App\Model\CategoryDto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category', methods: ['GET','HEAD'])]
    public function index(): Response
    {
        $categories = [new CategoryDto(id:1, name:"Categoria1"), new CategoryDto(2,"Categoria2")];

        return new JsonResponse($categories, Response::HTTP_CREATED);
    }
}
```

La anotación "Route" define la ruta a la que debe atender nuestro controlador, así como los métodos del protocolo HTTP que atenderá nuestra ruta. 

Symfony tiene un fichero donde indicamos las rutas de los controladores y algunos parámetros más. Es conveniente que revises el archivo *html/config/routes.yaml*.

```YAML
controllers:
    resource: ../src/Controller/
    type: attribute
```

Ahora puedes ver resultado del controlador en la url en el navegador o con postman [http://nombre-del-proyecto.local/category](http://nombre-del-proyecto.local/category)

Nuestro controlador ahora tiene unas anotaciones nuevas para especificar los métodos y ahora la respuesta no es HTML generado con una plantilla TWIG, sino un JsonResponse como espera que responda la api.

Pero hasta ahora solo devuelve la misma lista de Categorias que hemos creado. Debemos añadir otras acciones para que nuestra API se más funcional.

