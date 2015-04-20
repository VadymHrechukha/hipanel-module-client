<?php
/**
 * @link    http://hiqdev.com/hipanel-module-client
 * @license http://hiqdev.com/hipanel-module-client/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            // you may configure additional properties here
        ],
        'name',
        'email',
        [
            'class' => 'yii\grid\ActionColumn',
            'template'=>'{view}',
            'buttons'=>[
                'view'=>function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['view','id'=>$model['id']]);
                    },
            ],
        ],
    ],
]); ?>
