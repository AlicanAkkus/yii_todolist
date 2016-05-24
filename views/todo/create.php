<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model AlicanAkkus\yii_todolist\models\Todo */

$this->title = 'Create Todo';
$this->params['breadcrumbs'][] = ['label' => 'Todos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="todo-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
