# Установка через Docker

Скачать файлы:

    git clone https://github.com/max-prot/wall-history-test.git .

Запустите сборку контейнеров:

    docker-compose up --build

Войдите внутрь контейнера с помощью консоли: 

    docker exec -it wall_history_php bash

Установить зависимости:

    composer install

Запустите настройку окружения с помощью команды:

    php init

   Выберите: `[0] Development`

Произведите миграции:

    php yii migrate

Данные для авторизации — можно посмотреть в файле: `config/users.php`.

Адрес авторизации: `http://localhost:8001/site/login`