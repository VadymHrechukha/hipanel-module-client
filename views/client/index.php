<?php
/**
 * @link    http://hiqdev.com/hipanel-module-client
 * @license http://hiqdev.com/hipanel-module-client/license
 * @copyright Copyright (c) 2015 HiQDev
 */

use hipanel\grid\BoxedGridView;
use hipanel\grid\CheckboxColumn;
use hipanel\grid\CurrentColumn;
use hipanel\grid\EditableColumn;
use hipanel\widgets\Box;
use hipanel\modules\client\grid\ResellerColumn;
use hipanel\modules\domain\widgets\State;
use yii\bootstrap\ButtonGroup;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;

$this->title = 'Client';
$this->params['breadcrumbs'][] = $this->title;

$widgetButtonConfig = [
    'buttons' => [
        [
            'label' => Yii::t('app', 'Tariff'),
            'options' => [
                'class' => 'btn-default' . ($add['tpl'] == '_tariff' ? ' active' : ''),
                'data-view' => '_tariff'
            ],
        ],
        [
            'label' => Yii::t('app', 'Card'),
            'options' => [
                'class' => 'btn-default' . ($add['tpl'] == '_card' ? ' active' : ''),
                'data-view' => '_card',
            ],
        ],
    ],
    'options' => ['class' => 'change-view-button']
];
?>
<?php $box = Box::begin(); ?>
<?= ButtonGroup::widget($widgetButtonConfig); ?>
<?php $box::end(); ?>

<?php
$widgetIndexConfig = [
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => CheckboxColumn::className(),
        ],
        [
            'class' => CurrentColumn::className(),
            'uses' => [
                'rename' => [
                    'text' => 'login',
                ],
                'return' => ['id'],
                'term' => 'client_like:term',
                'wrapper' => 'results',
            ],
            'attribute' => 'login',
            'label' => Yii::t('app', 'Client'),
            'value' => function ($model) {
                return Html::a($model->login, ['view', 'id' => $model->id]);
            },
        ],
        [
            'class' => ResellerColumn::className(),
        ],
        [
            'attribute' => 'type',
            'label' => Yii::t('app', 'Type'),
            'filter' => Html::activeDropDownList($searchModel, 'type', \hipanel\models\Ref::getList('type,client', true), [
                'class' => 'form-control',
                'prompt' => Yii::t('app', '--'),
            ]),
        ],
        [
            'attribute' => 'state',
            'label' => Yii::t('app', 'State'),
            'filter' => Html::activeDropDownList($searchModel, 'state', \hipanel\models\Ref::getList('state,client', true), [
                'class' => 'form-control',
                'prompt' => Yii::t('app', '--'),
            ]),
        ],
        'email',
    ],
];
switch ($add['tpl']) {
    case '_card':
        $widgetIndexConfig['columns'] = ArrayHelper::merge($widgetIndexConfig['columns'], [
            [
                'attribute' => 'name',
                'label' => Yii::t('app', 'Name'),
            ],
            [
                'label' => Yii::t('app', 'Balance'),
                'format' => 'html',
                'value' => function ($data) {
                    return Yii::t('app', 'Balance') . ": " . HTML::tag('span', $data->balance, $data->balance < 0 ? 'color="red"' : '');
                },
            ],
            [
                'class' => EditableColumn::className(),
                'attribute' => 'credit',
                'filter' => false,
                'popover' => Yii::t('app', 'Set credit'),
                'action' => ['set-credit']
            ],
            [
                'label' => Yii::t('app', 'Credit'),
            ],
        ]);
        break;
    case '_tariff':
        $widgetIndexConfig['columns'] = ArrayHelper::merge($widgetIndexConfig['columns'], [
            [
                'attribute' => 'tariff_name',
                'format' => 'html',
                'filter' => Html::dropDownList('tariff_name', ['1'], [1 => 1, 2 => 2], [
                    'class' => 'form-control',
                    'prompt' => Yii::t('app', 'BACKEND_PROMPT_STATUS'),
                ]),
            ],
        ]);
        break;
    default:
        $widgetIndexConfig['columns'] = ArrayHelper::merge($widgetIndexConfig['columns'], [
            [
                'attribute' => 'name',
                'label' => Yii::t('app', 'Name'),
            ],
            [
                'label' => Yii::t('app', 'Balance'),
                'format' => 'html',
                'value' => function ($data) {
                    return Yii::t('app', 'Balance') . ": " . HTML::tag('span', $data->balance, $data->balance < 0 ? 'color="red"' : '');
                },
            ],
            [
                'class' => EditableColumn::className(),
                'attribute' => 'credit',
                'filter' => false,
                'popover' => Yii::t('app', 'Set credit'),
                'action' => ['set-credit']
            ],
            [
                'label' => Yii::t('app', 'Credit'),
            ],
        ]);
        break;

}

$widgetIndexConfig['columns'] = ArrayHelper::merge($widgetIndexConfig['columns'], [
    [
        'attribute' => 'create_time',
        'label' => Yii::t('app', 'Create date'),
        'format' => ['date', 'php:Y-m-d'],
        'filter' => DatePicker::widget([
            'model' => $searchModel,
            'name' => 'create_time',
            'dateFormat' => 'yyyy-MM-dd',
            'attribute' => 'create_time',
        ]),
    ],
    [
        //        'class'         => 'yii\grid\ActionColumn',
        'class' => 'hipanel\grid\ActionColumn',
        'template' => '{view} {update} {block} {delete} {set-credit}',
        'buttons' => [
            //            'view'          => function ($url, $model, $key) {
            //                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view','id'=>$model['id']]);
            //            },
            'block' => function ($url, $model, $key) {
                if ($model->state == 'ok')
                    return Html::a(Yii::t('app', 'Block'), [
                        'enable-block',
                        'id' => $model['id']
                    ], [
                        'class' => "client-block enable",
                        'title' => Yii::t('app', 'Enable') . " " . Yii::t('app', 'block'),
                        'value-id' => $model['id']
                    ]);
                elseif ($model->state == 'blocked')
                    return Html::a(Yii::t('app', 'Block'), [
                        'disable-block',
                        'id' => $model['id']
                    ], [
                        'class' => "client-block disable",
                        'title' => Yii::t('app', 'Disable') . " " . Yii::t('app', 'block'),
                        'value-id' => $model['id']
                    ]);
            },
            //            'delete'        => function ($url, $model, $key) {
            //                if ($model->state != 'deleted') return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete','id'=>$model['id']],
            //                    [ 'title' => Yii::t('app', 'Delete'), 'data-pjax' => 0, 'class' => 'delete-client', 'value-id' => $model['id'] ] );
            //            },
            'set-credit' => function ($url, $model, $key) {
                return Html::a(Yii::t('app', 'Set credit'), [
                    'set-credit',
                    'id' => $model['id']
                ], ['class' => 'set-credit', 'title' => Yii::t('app', 'Set credit'), 'value-id' => $model['id']]);
            }
        ],
    ],
]);

?>

<?= BoxedGridView::widget($widgetIndexConfig); ?>

<?php
echo Html::beginForm([
    'set-credit',
], "POST", ['data' => ['pjax' => 1], 'class' => 'inline', 'id' => 'set-credit-form']);

Modal::begin([
    'id' => 'set-credit-form-modal',
    'header' => Html::tag('h4', Yii::t('app', 'Set credits for user')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer' => Html::button(Yii::t('app', 'Set credit'), [
        'class' => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Setting...'),
        'onClick' => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ]),
]); ?>
<div id="set-credit-contents"></div>
<?php
Modal::end();
echo Html::endForm();
?>

<?= Html::beginForm([
    'delete',
], "GET", ['data' => ['pjax' => 1], 'class' => 'inline', 'id' => 'delete-client']);

?>
<input type="hidden" class='delete-value' readonly='readonly' name="id"/>
<?php
Modal::begin([
    'id' => 'delete-client-form-modal',
    'header' => Html::tag('h4', Yii::t('app', 'Delete client')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer' => Html::button(Yii::t('app', 'Delete client'), [
        'class' => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Setting...'),
        'onClick' => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ]),
]);
Modal::end();
echo Html::endForm();
?>

<?= Html::beginForm([
    'enable-block',
], "POST", ['data' => ['pjax' => 1], 'class' => 'inline', 'id' => 'block-client']);

?>
<?php
Modal::begin([
    'id' => 'enable-block-client-form-modal',
    'header' => Html::tag('h4', Yii::t('app', 'Block client')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer' => Html::button(Yii::t('app', 'Block'), [
        'class' => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Setting...'),
        'onClick' => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ]),
]); ?>
<div id='enable-block-content'></div>
<?php
Modal::end();
echo Html::endForm();
?>

<?= Html::beginForm([
    'disable-block',
], "POST", ['data' => ['pjax' => 1], 'class' => 'inline', 'id' => 'disable-client']);

?>
<?php
Modal::begin([
    'id' => 'disable-block-client-form-modal',
    'header' => Html::tag('h4', Yii::t('app', 'Unblock client')),
    'headerOptions' => ['class' => 'label-warning'],
    'footer' => Html::button(Yii::t('app', 'Unblock'), [
        'class' => 'btn btn-warning',
        'data-loading-text' => Yii::t('app', 'Setting...'),
        'onClick' => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
    ]),
]); ?>
<div id='disable-block-content'></div>
<?php
Modal::end();
echo Html::endForm();
?>


<?php $this->registerJs("
    $( document ).on('click', '.change-view-button button', function() {
        var view = $(this).data('view');

        if ( view == '_tariff' )
            location.replace('" . Url::toRoute(['index', 'tpl' => '_tariff']) . "');
        else
            location.replace('" . Url::toRoute(['index', 'tpl' => '_card']) . "');
    });

    $( document ).on('click', '.delete-client', function () {
        $('#delete-client-form-modal').modal('show');
        $('#delete-client-form-modal').closest('form').attr('action', '/client/client/delete?id='+$(this).attr('value-id'));
        $('#delete-client-form-modal').closest('form').find('input.delete-value').val($(this).attr('value-id'));
        return false;
    });

    $( document ).on('click', '.set-credit', function () {
        var id = $(this).attr('value-id');
        $.ajax({
            url:    '/client/client/set-credit',
            data:   {id : id },
            type:   'POST',
            success: function (data) {
                $('#set-credit-form-modal').closest('form').find('input[name=_csrf]').remove();
                $('#set-credit-contents').html(data);
                $('#set-credit-form-modal').modal('show');
            },
            error: function () {
                 location.href = '/client/client/set-credit?id='+id;
           }
        });
        return false;
    });

    $( document ).on('click', '.client-block', function () {
        var action = $(this).hasClass('enable') ? 'enable' : 'disable';
        var id = $(this).attr('value-id');
        $.ajax({
            url:    '/client/client/'+action+'-block',
            data:   {id : $(this).attr('value-id') },
            type:   'POST',
            success: function (data) {
                $('#'+action +'-block-client-form-modal').closest('form').find('input[name=_csrf]').remove();
                $('#' + action +'-block-client-form-modal').modal('show');
                $('#'+action+'-block-content').html(data);
            },
            error: function () {
                location.href = '/client/client/'+action+'-block?id='+id;
            }
        });
        return false;
    });

", View::POS_END, 'view-options'); ?>
