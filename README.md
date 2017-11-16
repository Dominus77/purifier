HTML Purifier Converter
=======================

Пример модуля инструмента обработки текста с помощью [HTML Purifier](http://www.yiiframework.com/doc-2.0/yii-helpers-htmlpurifier.html)

Подключение:

backend/config/main.php
```
$config = [
    //...
    'bootstrap' => [
        'log',
        //...
        'modules\purifier\Bootstrap',
    ],
    'modules' => [
        //...
        'purifier' => [
            'class' => 'modules\purifier\Module',
        ],
    ],
    //...
];
```
Ссылка:
```
Url::to(['/purifier/default/index']);
```

