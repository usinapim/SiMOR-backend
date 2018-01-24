SiMORA-backend-branch
====================

A Symfony project created on December 15, 2015, 7:37 pm.

Created for hidrate app [SiMORA](https://play.google.com/store/apps/details?id=org.pim.simor)

prerequistes:
- php >= 5.5
- mysql
- composer: https://getcomposer.org/

1. clone proyect.

in poryect path runs 
```
$ composer install
```

when ready create database

```
$ php app/console doc:dat:create
```

and then create user

```
$ php app/console fos:user:create
```

Enjoy!

> to autoimports datos from [Prefectura](http://www.prefecturanaval.gov.ar/alturas/index.php) 

in linux run 
```
$ crontab -e
```
and add
```
0 10 * * * php {your_proyect_path}/app/console rio:importar:prefectura > /dev/null 2>&1
```
