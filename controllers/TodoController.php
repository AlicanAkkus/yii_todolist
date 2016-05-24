<?php

namespace AlicanAkkus\yii_todolist\controllers;

use Yii;
use AlicanAkkus\yii_todolist\models\Todo;
use AlicanAkkus\yii_todolist\models\TodoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
 use yii\filters\AccessControl; 



/**
 * TodoController implements the CRUD actions for Todo model.
 */
class TodoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
         return [ 
           'access' => [ 
                'class' => AccessControl::className(),                 
                 'rules' => [    
                     /*[                          'actions' => ['index','view','create','update','delete'], 
                         'allow' => true, 
                         'roles' => ['?'], 
                     ],  //LİST VİEW OLURSA BURADA GÖSTER*/                
                     [ 
                        'actions' => ['index','view','create','update','delete','comment','removecomment'], 
                         'allow' => true, 
                         'roles' => ['@'], 
  
                     ], 
                 ], 
             ], 
             'verbs' => [ 
                 'class' => VerbFilter::className(), 
                 'actions' => [ 
                     'delete' => ['post'], 
                 ], 
             ], 
         ]; 
     } 


    /**
     * Lists all Todo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TodoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Todo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Todo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new Todo();
			$model->id=mt_rand(1,10000);
			$model->userId = Yii::$app->user->getId();
		if (Yii::$app->user->can('createContent', ['Todo' => $model])) {
			

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('create', [
					'model' => $model,
				]);
			}
		}else{ 
             Yii::$app->session->setFlash('error', 'Sadece kendi içeriklerinizi güncelleyebilirsiniz!'); 
             $this->redirect(['index']); 
        } 
    }

    /**
     * Updates an existing Todo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->can('updateContent', ['Todo' => $model])) {
			 if ($model->load(Yii::$app->request->post()) && $model->save()) {
				return $this->redirect(['view', 'id' => $model->id]);
			} else {
				return $this->render('update', [
					'model' => $model,
				]);
			}
		}else{ 
             Yii::$app->session->setFlash('error', 'Sadece kendi içeriklerinizi güncelleyebilirsiniz!'); 
             $this->redirect(['index']); 
        } 

    }

    /**
     * Deletes an existing Todo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		if (Yii::$app->user->can('deleteContent', ['Todo' => $this->findModel($id)])) {
			$this->findModel($id)->delete();

			return $this->redirect(['index']);
		
		}else{ 
             Yii::$app->session->setFlash('error', 'Sadece kendi içeriklerinizi güncelleyebilirsiniz!'); 
             $this->redirect(['index']); 
        } 
    }
	
	public function actionComment()
    {	
		$commentId = mt_rand(1,10000);
		$comment =  $_GET["comment"];
		$todoId = $_GET["todoId"];
		$todoUser = $_GET["todoUser"];
		
		$conn = mysqli_connect("localhost","root","","advanced");
		$result =  mysqli_query($conn, "insert into comments(commentId, todoId, comment, comment_date, comment_by) values('$commentId','$todoId','$comment', NOW(), '$todoUser')");
		mysqli_close($conn);
		if ($result) {
			echo "entered comments!";
			return $this->render('view', [
				'model' => $this->findModel($todoId),
			]);
		}else{
			echo "couldt not entered comments!";
		}
       
    }
	
	public function actionRemovecomment()
    {	
		//$commentId = mt_rand(1,10000);
		$commentId =  $_GET["commentId"];
		$todoId = $_GET["todoId"];
		//$todoUser = $_GET["todoUser"];
		
		$conn = mysqli_connect("localhost","root","","advanced");
		$result =  mysqli_query($conn, "DELETE FROM comments WHERE commentId = $commentId and todoId = $todoId");
		mysqli_close($conn);
		if ($result) {
			echo "deleted comments!";
			return $this->render('view', [
				'model' => $this->findModel($todoId),
			]);
		}else{
			echo "couldt not deleted comments!";
		}
       
    }

    /**
     * Finds the Todo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Todo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Todo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
}
