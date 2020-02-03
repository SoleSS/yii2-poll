# yii2-poll

## Installation

composer require --prefer-dist soless/yii2-poll "*"

php yii migrate/up --migrationPath=@vendor/soless/yii2-poll/migrations

add to config:
```
    'modules' => [
        'poll' => [
            'class' => '\soless\poll\Module',
        ]
    ],
```

## Available CRUD controllers:

