<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model AlicanAkkus\yii_todolist\models\Todo */

$this->title = 'Update Todo: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Todos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="todo-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
