<?php

namespace  AlicanAkkus\yii_todolist\controllers;

use yii\web\Controller;

/**
 * Default controller for the `todo` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
