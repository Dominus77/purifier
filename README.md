HTML Purifier Converter
=======================

Инструмент обработки текста с помощью [HTML Purifier](http://www.yiiframework.com/doc-2.0/yii-helpers-htmlpurifier.html) и пакетного сохранения в указанную колонку модели.

По умолчанию в модуле доступно:
* Удаление HTML тэгов, указываются в поле через запятую;
* Удаление атрибутов тегов, указываются в поле через запятую;
* Обработка текста HTML Purifier по умолчанию;
* Предпросмотр перед сохранением;

Всё это дело настраивается в модели [PurifierForm](https://github.com/Dominus77/purifier/blob/master/models/PurifierForm.php)

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

License
-----
The BSD License (BSD). Please see [License File](https://github.com/Dominus77/purifier/blob/master/LICENSE.md) for more information.
