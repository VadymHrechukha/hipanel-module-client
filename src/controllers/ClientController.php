<?php

/*
 * Client Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-client
 * @package   hipanel-module-client
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (https://hiqdev.com/)
 */

namespace hipanel\modules\client\controllers;

use Yii;
use yii\web\Response;

class ClientController extends \hipanel\base\CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'hipanel\actions\IndexAction',
            ],
            'create' => [
                'class' => 'hipanel\actions\SmartCreateAction',
                'success' => Yii::t('app', 'Client is created'),
            ],
            'update' => [
                'class'     => 'hipanel\actions\SmartUpdateAction',
                'success'   => Yii::t('app', 'Client is updated'),
            ],
            'delete' => [
                'class' => 'hipanel\actions\SmartPerformAction',
                'success' => Yii::t('app', 'Client is deleted'),
            ],
            'view' => [
                'class' => 'hipanel\actions\ViewAction',
                'findOptions' => [
                    'with_tickets_count' => 1,
                    'with_domains_count' => 1,
                    'with_servers_count' => 1,
                    'with_hosting_count' => 1,
                    'with_contacts_count' => 1,
                    'with_last_seen' => 1,
                    'with_contact' => 1,
                ],
            ],
            'validate-form' => [
                'class' => 'hipanel\actions\ValidateFormAction',
            ],
            'set-credit' => [
                'class' => 'hipanel\actions\SmartUpdateAction',
                'success' => Yii::t('app', 'Credit changed'),
            ],
        ];
    }

    /// TODO: implement
//    public function actionCheckLogin($login)
//    {
//        Yii::$app->response->format = Response::FORMAT_JSON;
//
//        return $out;
//    }
}
