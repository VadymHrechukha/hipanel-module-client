<?php

use hipanel\widgets\BulkOperation;

echo BulkOperation::widget(array_merge([
    'model' => $model,
    'models' => $models,
    'affectedObjects' => Yii::t('hipanel:client', 'Affected clients'),
    ], $bulkOp));

