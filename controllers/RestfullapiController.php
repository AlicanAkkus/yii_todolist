<?php

namespace AlicanAkkus\yii_todolist\controllers;

use Yii;
use yii\web\Controller;
use yii\rest\ActiveController;

class RestfullapiController extends ActiveController
{
	public $modelClass = 'AlicanAkkus\yii_todolist\models\Todo';
}