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

        // add "createPost" permission
        $createContent = $auth->createPermission('createContent');
        $createContent->description = 'Create a content';
        $auth->add($createContent);

        // add "updatePost" permission
        $updateContent = $auth->createPermission('updateContent');
        $updateContent->description = 'Update content';
        $auth->add($updateContent);

       /* // add "deletePost" permission
        $deleteContent = $auth->createPermission('deleteContent');
        $deleteContent->description = 'Delete content';
        $auth->add($deleteContent);*/

         // add "deletePost" permission
        $deleteContent = $auth->createPermission('deleteContent');
        $deleteContent->description = 'Delete content';
        $auth->add($deleteContent);
		
		// add "deletePost" permission
        $createComment = $auth->createPermission('comment');
        $createComment->description = 'Create Comment';
        $auth->add($createComment);

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('author');
        $auth->add($author);
        $auth->addChild($author, $createContent);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateContent);
        $auth->addChild($admin, $deleteContent);
        $auth->addChild($admin, $author);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($author, 2);
        $auth->assign($admin, 1);
    }
	
	public function actionAuthorRule()
	{
		$auth = Yii::$app->authManager;

		// add the rule
		$rule = new AuthorRule;
		$auth->add($rule);

		// add the "updateOwnPost" permission and associate the rule with it.
		$updateOwnContent = $auth->createPermission('updateOwnContent');
		$updateOwnContent->description = 'Update own content';
		$updateOwnContent->ruleName = $rule->name;
		$auth->add($updateOwnContent);

		// "updateOwnPost" will be used from "updatePost"
		$updateContent = $auth->getPermission('updateContent');
		$auth->addChild($updateOwnContent, $updateContent);

        // add the "deleteOwnPost" permission and associate the rule with it.
        $deleteOwnContent = $auth->createPermission('deleteOwnContent');
        $deleteOwnContent->description = 'Delete own content';
        $deleteOwnContent->ruleName = $rule->name;
        $auth->add($deleteOwnContent);

        // "deleteOwnPost" will be used from "deletePost"
        $deleteContent = $auth->getPermission('deleteContent');
        $auth->addChild($deleteOwnContent, $deleteContent);

        // allow "author" to update their own posts
        $author = $auth->getRole('author');
        $auth->addChild($author, $deleteOwnContent);
        $auth->addChild($author, $updateOwnContent);
	}
}