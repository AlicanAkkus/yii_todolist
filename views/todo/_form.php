<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model AlicanAkkus\yii_todolist\models\Todo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="todo-form">


	<div class="panel panel-success">
		<div class="panel-heading">
			<?= $model->isNewRecord ? 'Create Todo' : 'Update Todo' ?>
		</div>
		
		<div class="panel-body">
				<?php $form = ActiveForm::begin(); ?>

				<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

				<?= $form->field($model, 'status')->textInput() ?>

				<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>


				<div class="form-group">
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>

				<?php ActiveForm::end(); ?>
		</div>
	</div>

   

</div>
