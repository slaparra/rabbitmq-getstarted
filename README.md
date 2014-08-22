rabbittmq-training
==================

Installation
------------

    git clone https://github.com/oriolgm/rabbitmq-training.git

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

    php bin/console rabbitmq:consumer --type="error"

    php bin/console rabbitmq:consumer --type="info"


