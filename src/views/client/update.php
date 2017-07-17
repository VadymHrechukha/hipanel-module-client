<?php

use hipanel\widgets\Box;

$this->title = Yii::t('hipanel', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel', 'Client'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->login, 'url' => ['@client/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row col-md-4">
    <?php Box::begin() ?>
        <?= $this->render('_form', ['model' => $model]) ?>
    <?php Box::end() ?>
</div>

