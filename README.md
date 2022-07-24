
# Image Service App
The application was developed as part of selection process for [Anwalt](https://www.anwalt.de/). The functionality that this application covers are resizing and cropping image which is present on the server.
### # Prerequisites

 - [docker](https://www.docker.com/)
 - docker-compose (this comes with docker installation in most cases, but if you don't have it you can install it from [here](https://docs.docker.com/compose/install/))
 - make (sudo apt-get install build-essential)

### # Installation
Go to project directory and run:

#### via make
    make
#### via docker-compose

    docker-compose up -d
The application will be accessible on localhost port 8756

    http://localhost:8756/

 In case this port is being in use, change the port in docker-compose.yml
### # Core

##### Router/Router.php
Each request goes thru this class and is being routed to corresponding controller and action. Routes are defined in $routes property

    private $routes = [
        [
            'path' => '/',
            'controller' => HomeController::class,
            'action' => 'index',
            'method' => 'GET'
        ]
    ];
##### Controller/
Application endpoints 
##### Class/
Entities such as Image class that is currently present in code.
##### Service/
Classes that hold the logic and manipulate entities and do the calculations.

### # Endpoints
#### # [GET] http://localhost:8756/
Without query parameters application will render cropped and resized image if they exist.

For manipulating the image query parameters need to be provided.
Crop:
 - type: crop
 - width
 - height
 - x
 - y
 
Example: http://localhost:8756/?type=crop&x=20&y=200&width=200&height=400

*Resize:*
 - type: resize
 - width
 - height
 
Example: http://localhost:8756/?type=resize&width=200&height=400
