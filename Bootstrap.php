<?php

namespace modules\purifier;

use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package modules\purifier
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // i18n
        $app->i18n->translations['modules/purifier/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'basePath'       => '@modules/purifier/messages',
            'fileMap'        => [
                'modules/purifier/module' => 'module.php'
            ],
        ];

        // Rules
        $app->getUrlManager()->addRules(
            [
                // объявление правил здесь
                'purifier' => 'purifier/default/index',
                'purifier/<_a:[\w\-]+>' => 'purifier/default/<_a>',
            ]
        );
    }
}