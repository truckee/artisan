artisan
=======

A Symfony project created on November 17, 2017, 10:06 am.

### Installing

If you want to install this someplace other than DreamHost, see the deployment instructions for Symfony 3.4 applications [here](https://symfony.com/doc/3.4/deployment.html).

For DreamHost installation instructions see the [How To..](https://discussion.dreamhost.com/t/how-to-install-a-symfony-application-in-a-non-vps-hosted-domain/66037/1 "Installing artisan") at the DreamHost forum.  That entry was made as a result of the installation of this application at DreamHost.

### Maintaining

#### For installations with composer, assuming initial git cloning installation:

`$ git pull origin master`

`$ composer update`

#### For DreamHost installations:

install locally using composer to generate the `../vendor` directory:

    `$ git pull origin master`

Create a tarball of the vendor directory

Upload tarball to the project directory

    `$ tar -xf ../vendor.tar.gz -C .`

Clear cache: `$ php bin/console cache:clear -e=prod`
