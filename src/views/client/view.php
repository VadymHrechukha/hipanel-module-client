<?php

use hipanel\helpers\FontIcon;
use hipanel\modules\client\grid\ClientGridView;
use hipanel\modules\client\grid\ContactGridView;
use hipanel\modules\client\models\Client;
use hipanel\modules\client\models\Contact;
use hipanel\widgets\BlockModalButton;
use hipanel\widgets\Box;
use hipanel\widgets\SettingsModal;
use hiqdev\assets\flagiconcss\FlagIconCssAsset;
use yii\helpers\Html;

/*
 * @var $model Client
 */

$this->title = $model->login;
$this->subtitle = Yii::t('app', 'Client detailed information') . ' #' . $model->id;
$this->breadcrumbs->setItems([
    ['label' => Yii::t('app', 'Clients'), 'url' => ['index']],
    $this->title,
]);

FlagIconCssAsset::register($this);

$this->registerCss('legend {font-size: 16px;}');

?>
<div class="row">
    <div class="col-md-3">
        <?php Box::begin([
            'options' => [
                'class' => 'box-solid',
            ],
            'bodyOptions' => [
                'class' => 'no-padding',
            ],
        ]); ?>
        <div class="profile-user-img text-center">
            <?= $this->render('//layouts/gravatar', ['email' => $model->email, 'size' => 120]); ?>
        </div>
        <p class="text-center">
            <span class="profile-user-name">
                <?= \hipanel\widgets\ClientSellerLink::widget([
                    'model' => $model,
                    'clientAttribute' => 'login',
                    'clientIdAttribute' => 'id',
                ]) ?>
            </span>
            <br>
            <span class="profile-user-role"><?= $model->type ?></span><br>
        </p>

        <div class="profile-usermenu">
            <ul class="nav">
                <li>
                    <a href="http://gravatar.com" target="_blank">
                        <i><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000/?s=17" /></i>
                        <?= Yii::t('app', 'You can change your avatar at Gravatar.com')?>
                    </a>
                </li>
                <li>
                    <?= SettingsModal::widget([
                        'model'    => $model,
                        'title'    => Yii::t('app', 'Change password'),
                        'icon'     => 'fa-key fa-flip-horizontal fa-fw',
                        'scenario' => 'change-password',
                    ]) ?>
                </li>
                <?php if (Yii::$app->user->id === $model->id) : ?>
                    <li>
                        <?= SettingsModal::widget([
                            'model'    => $model,
                            'title'    => Yii::t('app', 'Pincode settings'),
                            'icon'     => 'fa-puzzle-piece fa-fw',
                            'scenario' => 'pincode-settings',
                        ]) ?>
                    </li>
                <?php endif ?>
                <li>
                    <?= SettingsModal::widget([
                        'model'    => $model,
                        'title'    => Yii::t('app', 'IP address restrictions'),
                        'icon'     => 'fa-arrows-alt fa-fw',
                        'scenario' => 'ip-restrictions',
                    ]) ?>
                </li>
                <li>
                    <?= SettingsModal::widget([
                        'model'    => $model,
                        'title'    => Yii::t('app', 'Mailing settings'),
                        'icon'     => 'fa-envelope fa-fw',
                        'scenario' => 'mailing-settings',
                    ]) ?>
                </li>
                <li>
                    <?= Html::a(FontIcon::i('fa-edit fa-fw') . Yii::t('app', 'Change contact information'), ['@contact/update', 'id' => $model->id]) ?>
                </li>
                <?php if (Yii::getAlias('@domain', false)) : ?>
                    <li>
                        <?= SettingsModal::widget([
                            'model'    => $model,
                            'title'    => Yii::t('app', 'Domain settings'),
                            'icon'     => 'fa-globe fa-fw',
                            'scenario' => 'domain-settings',
                        ]) ?>
                    </li>
                <?php endif ?>
                <?php if (Yii::getAlias('@ticket', false)) : ?>
                    <li>
                        <?= SettingsModal::widget([
                            'model'    => $model,
                            'title'    => Yii::t('app', 'Ticket settings'),
                            'icon'     => 'fa-ticket fa-fw',
                            'scenario' => 'ticket-settings',
                        ]) ?>
                    </li>
                <?php endif ?>
                <?php if (Yii::$app->user->can('support') && Yii::$app->user->not($model->id)) : ?>
                    <li>
                        <?= BlockModalButton::widget(compact('model')) ?>
                    </li>
                <?php endif ?>
            </ul>
        </div>
        <?php Box::end(); ?>
    </div>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6">
                <?php $box = Box::begin(['renderBody' => false]) ?>
                    <?php $box->beginHeader() ?>
                        <?= $box->renderTitle(Yii::t('app', 'Client information'), '&nbsp;') ?>
                        <?php $box->beginTools() ?>
                        <?php $box->endTools() ?>
                    <?php $box->endHeader() ?>
                    <?php $box->beginBody() ?>
                        <?= ClientGridView::detailView([
                            'boxed' => false,
                            'model' => $model,
                            'columns' => [
                                'seller_id', 'name',
                                'type', 'state',
                                'create_time', 'update_time',
                                'tickets', 'servers', 'domains', 'contacts', 'hosting',
                            ],
                        ]) ?>
                    <?php $box->endBody() ?>
                <?php $box->end() ?>
                <?php foreach ($model->purses as $purse) : ?>
                    <?php if (isset($purse['balance'])) : ?>
                        <?= $this->render('@hipanel/modules/finance/views/bill/_purseBlock', compact('purse')) ?>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
            <div class="col-md-6">
                <?php $box = Box::begin(['renderBody' => false]); ?>
                    <?php $box->beginHeader(); ?>
                        <?= $box->renderTitle(Yii::t('app', 'Contact information'), ''); ?>
                        <?php $box->beginTools(); ?>
                            <?= Html::a(Yii::t('app', 'Details'), ['@contact/view', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']) ?>
                            <?= Html::a(Yii::t('app', 'Change'), ['@contact/update', 'id' => $model->id], ['class' => 'btn btn-default btn-xs']) ?>
                        <?php $box->endTools(); ?>
                    <?php $box->endHeader(); ?>
                    <?php $box->beginBody(); ?>
                        <?= ContactGridView::detailView([
                            'boxed' => false,
                            'model' => new Contact($model->contact),
                            'columns' => [
                                'first_name', 'last_name', 'organization',
                                'email', 'abuse_email', 'messengers',
                                'voice_phone', 'fax_phone',
                                'street', 'city', 'province', 'postal_code', 'country',
                            ],
                        ]) ?>
                    <?php $box->endBody(); ?>
                <?php $box->end(); ?>
            </div>
        </div>
    </div>
</div>
