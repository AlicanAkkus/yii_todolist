<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\rbac\AuthorRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // add "createTodo" permission
        $createTodo = $auth->createPermission('createTodo');
        $createTodo->description = 'Create a todo';
        $auth->add($createTodo);

        // add "updateTodo" permission
        $updateTodo = $auth->createPermission('updateTodo');
        $updateTodo->description = 'Update todo';
        $auth->add($updateTodo);

       /* // add "deleteTodo" permission
        $deleteTodo = $auth->createPermission('deleteTodo');
        $deleteTodo->description = 'Delete todo';
        $auth->add($deleteTodo);*/

         // add "deletePost" permission
        $deleteTodo = $auth->createPermission('deleteTodo');
        $deleteTodo->description = 'Delete todo';
        $auth->add($deleteTodo);
		
		// add "createComment" permission
        $createComment = $auth->createPermission('createComment');
        $createComment->description = 'Create Comment';
        $auth->add($createComment);

        // add "todo own" role and give this role the "createTodo" permission
        $todoown = $auth->createRole('todoown');
        $auth->add($todoown);
        $auth->addChild($todoown, $createTodo);

        // add "todo admin" role and give this role the "updateTodo" permission
        // as well as the permissions of the "todoown" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateTodo);
        $auth->addChild($admin, $deleteTodo);
        $auth->addChild($admin, $todoown);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($todoown, 2);
        $auth->assign($admin, 1);
    }
	
	public function actionAuthorRule()
	{
		$auth = Yii::$app->authManager;

		// add the rule
		$rule = new AuthorRule;
		$auth->add($rule);

		// add the "updateOwnTodo" permission and associate the rule with it.
		$updateOwnTodo = $auth->createPermission('updateOwnTodo');
		$updateOwnTodo->description = 'Update own todo';
		$updateOwnTodo->ruleName = $rule->name;
		$auth->add($updateOwnTodo);

		// "updateOwnTodo" will be used from "updateTodo"
		$updateOwnTodo = $auth->getPermission('updateTodo');
		$auth->addChild($updateOwnTodo, $updateOwnTodo);

        // add the "deleteOwnTodo" permission and associate the rule with it.
        $deleteOwnTodo = $auth->createPermission('deleteOwnTodo');
        $deleteOwnTodo->description = 'Delete own todo';
        $deleteOwnTodo->ruleName = $rule->name;
        $auth->add($deleteOwnTodo);

        // "deleteTodo" will be used from "deleteTodo"
        $deleteTodo = $auth->getPermission('deleteTodo');
        $auth->addChild($deleteOwnTodo, $deleteTodo);

        // allow "todoown" to update their own posts
        $todoown = $auth->getRole('todoown');
        $auth->addChild($todoown, $deleteOwnTodo);
        $auth->addChild($todoown, $updateOwnTodo);
	}
}