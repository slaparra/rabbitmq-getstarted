rabbittmq-training 
===========https://github.com/slaparra/rabbitmq-getstarted/edit/master/README.md#=======
-- Atrapalo.com - Oriol GM: https://github.com/oriolgm/rabbitmq-training)

Installation
------------

    git clone https://github.com/slaparra/rabbitmq-getstarted.git

    cd rabbitmq-training

    curl -sS https://getcomposer.org/installer | php

    php composer.phar install

    php -S localhost:8080 web/index_dev.php

Usage
------------

Send a message

 http://localhost:8080/error/error%20message

 http://localhost:8080/info/info%20message

Consumer Command

    php bin/console rabbit:consumer

With Thumper

    php bin/console rabbitThumper:consumer --type=[error|info]

rabbit commands
------------

    /usr/local/sbin/rabbitmq-server -detached
    /usr/local/sbin/rabbitmqctl stop



urls producer
-------------

    localhost:8080/<type>/<message>
    localhost:8080/Thumper/<type>/<message>

urls admin rabbitmq
------------

    Admin rabbitmq => localhost:15672, user-pass: guest/guest
