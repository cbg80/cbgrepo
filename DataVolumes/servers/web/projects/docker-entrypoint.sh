#!/bin/bash
#Se situa en el directorio raíz de la aplicación desplegada
cd $1
#Elimina del proyecto todo el código correspondiente a las dependencias de aquel
rm -Rfv ./vendor
#Vuelve  a importar el código correspondiente a las dependencias del proyecto
composer install
#Actualiza el código correspondiente a las dependencias del proyecto
composer update

#Elimina del proyecto todos los recursos correspondientes a la documentación de este
rm -Rfv ./doc
#Vuelve a generar todos los recursos correspondientes a la documentación de este
./vendor/bin/phpdoc -d . -t ./doc --template="responsive" --title="$2" --sourcecode --ignore "vendor/*"

#Asigna al usuario apache como propietario de todo el contenido del proyecto
chown -R www-data:www-data 	./
#Asigna los permisos adecuados a todos los ficheros que constituyen el proyecto. Las dos siguientes líneas son mutuamente excluyentes. 
#La primera estará descomentada a la hora de desplegar un entorno de desarrollo. La segunda estará descomentada a la hora de desplegar 
#un entorno de producción
chmod -R 0664 ./
#chmod -R 0444 ./
#Asigna los permisos adecuados a todos los directorios que constituyen el proyecto. Las dos siguientes líneas son mutuamente excluyentes. 
#La primera estará descomentada a la hora de desplegar un entorno de desarrollo. La segunda estará descomentada a la hora de desplegar 
#un entorno de producción
find ./ -type d -iname "*" -print0 | xargs -I {} -0 chmod 0774 {}
#find ./ -type d -iname "*" -print0 | xargs -I {} -0 chmod 0544 {}
#La siguiente línea sólo será descomentada a la hora de desplegar un entorno de producción
#chmod -R 0744 ./assets/

#Asigna al superusuario como propietario del comando generador de la documentación del proyecto
chown root:root ./vendor/bin/phpdoc
#Asigna los permisos adecuados al comando generador de la documentación del proyecto
chmod 0744 ./vendor/bin/phpdoc

$3 $4 $5