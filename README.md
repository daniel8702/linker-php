Linker 
=============
This application is divided into three parts

1. Checking the status of given links and returning them, and check if redirect.
2. Checking whether the given WWW address and link exist and contain the kew word.
3. Checking if there are links at the given address

Enviroment
=============

An application tested only in the `Ubuntu linux` environment


Getting Started
=============

It is required to have `PHP 7.2` and `Composer`. It should be in the first place
 to install Compose packages:

```bash
composer install
```

Next run to Installing assets

```bash
$ yarn install
$ yarn build
$ ./node_modules/.bin/encore dev
```

At the end run application to start

```bash
$ bin/console server:start IP_ADDR:PORT
```

License
=======
[MIT License](LICENSE)
