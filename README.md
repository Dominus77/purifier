HTML Purifier Converter
=======================

Инструмент обработки текста с помощью [HTML Purifier](http://www.yiiframework.com/doc-2.0/yii-helpers-htmlpurifier.html) и пакетного сохранения в указанную колонку модели.

По умолчанию в модуле доступно:
* Удаление HTML тэгов, указываются в поле через запятую;
* Удаление атрибутов тегов, указываются в поле через запятую;
* Обработка текста HTML Purifier по умолчанию;
* Предпросмотр перед сохранением;

Всё это дело настраивается в модели [PurifierForm](https://github.com/Dominus77/purifier/blob/753ca900e903ab865f0cf0ed1a4356647c202151/models/PurifierForm.php#L117-L120)

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

