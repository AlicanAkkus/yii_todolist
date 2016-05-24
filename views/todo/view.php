<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model AlicanAkkus\yii_todolist\models\Todo */


$this->params['breadcrumbs'][] = ['label' => 'Todos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$username =  Yii::$app->user->identity->username;
$this->title = $username." todos ".$model->title;
$confirmMessage =  strtoupper($username) . ', Seçili Task\'ı silmek istediğinize emin misiniz?';

$status = (int)$model->status;
$completed = "";
//<span class="label label-primary">Primary Label</span>

if($status == 0){
	$completed = "<span class='label label-danger'>INCOMPLETED</span>";
}else if($status == 1){
	$completed = "<span class='label label-primary'>COMPLETED</span>";
}else{
	$completed = "UNKNOW";
}

class Comments
{
	public $commentId;
	public $todoId;
    public $comment;
    public $comment_by;
	public $comment_date;
}

$comments = array();

$conn = mysqli_connect("localhost","root","","advanced");
$commentSql = "select * from comments where todoId=".$model->id;
//echo $commentSql;
$resultComment =  mysqli_query($conn, $commentSql);
$commentDiv = "";
if (mysqli_num_rows($resultComment) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($resultComment)) {
       // $comment = new Comments();
		//$comment->comment = $row["comment"];
		//$comment->comment_by = $row["comment_by"];
		//$comment->comment_date = $row["comment_date"];
		
		$commentDiv = $commentDiv.'<div class="panel panel-info" onclick="">';
		$commentDiv = $commentDiv.'<div class="panel-heading"><form action="removecomment" method="get">';
		$commentDiv = $commentDiv.'<input type="hidden" name="commentId" value=\''.$row["commentId"].'\'>';
		$commentDiv = $commentDiv.'<input type="hidden" name="todoId" value=\''.$row["todoId"].'\'>';
		$commentDiv = $commentDiv.'<button type="button" class="btn btn-danger btn-sm" onclick="this.form.submit();">';
		$commentDiv = $commentDiv.'<span class="glyphicon glyphicon-remove">';
		$commentDiv = $commentDiv.'</span></button></form>Commented by  <b>'.$row["comment_by"].'</b> at <b>'.$row["comment_date"].'</b></div>';
		$commentDiv = $commentDiv.'<div class="panel-body">'.$row["comment"].'</div>';
		$commentDiv = $commentDiv.'</div>';
	
		//echo $row["comment_by"];
		//array_push($comments,$comment);
    }
} 
//echo $commentDiv;
mysqli_close($conn);

$conn = mysqli_connect("localhost","root","","advanced");
$result =  mysqli_query($conn, "select * from user where id=".$model->userId);
$todoUser = "";
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $todoUser = $row["username"];
    }
} else {
    $todoUser = "Unknow";
}
mysqli_close($conn);


?>
<div class="todo-view">
	
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				TODO Status : <?= $completed?> - TODO Date : <span class="badge"><?= $model->createDate?></span> - TODO Owner :  <span class="badge"><?= $todoUser ?></span>
				<div class="btn-group">
									
									<?= Html::a('Güncelle', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
									
									
									<?= Html::a('Sil', ['delete', 'id' => $model->id], [
											'class' => 'btn btn-danger',											
											'data' => [
												'confirm' => $confirmMessage,
												'method' => 'post',
											],
										]) ?>
									
				</div>
			</div>
			<div class="panel-body">			
				<div class="row">
					<div class="col-sm-7">
						<div class="panel panel-success">
							<div class="panel-heading">
								<b>TODO Title : </b> <?= $model->title ?>
							</div>
							<div class="panel-body">
								<?= $model->description ?>
							</div>
						</div>
					</div>				
					
					<div class="col-sm-5">	
							<div class="col-sm-9">
									<form action="comment" method="get">
										<input type="hidden" name="todoId" value="<?= $model->id?>">
										<input type="hidden" name="todoUser" value="<?= Yii::$app->user->identity->username ?>">
										<div class="form-horizontal">
											<div class="form-group">
												<div class="row">
													<div class="col-sm-9">
														<textarea name="comment" placeHolder="Add Comment.." rows="4" style="height: auto;" class="form-control"></textarea>
													</div>
													<div class="col-sm-3">
														<input type="button" value="Add.." onclick="this.form.submit();" class="btn-primary form-control">
													</div>
												</div>
											</div>
										</div>
									</form>
							</div>
						
					</div>
				</div>					
				
					<?=  $commentDiv?>										
						
			</div>
			
		</div>
	</div>
</div>
