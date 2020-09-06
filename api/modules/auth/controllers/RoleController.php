<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RoleController extends Controller
{
    /**
     *
     * https://e-tibb.az/api/auth/role
     */
    public function actionIndex()
    {
        return "salam";

    }
    /**
     * https://e-tibb.az/api/auth/role/create-permission
     * id=18
     */
    public function actionCreatePermission()
    {
        $auth = Yii::$app->authManager;
//        $auth->removeAll();

        // add "createPost" permission
        $index = $auth->createPermission('auth/post/index');
        $index->description = 'Create index';
        $auth->add($index);

        // add "updatePost" permission
        $create = $auth->createPermission('auth/post/create');
        $create->description = 'create post';
        $auth->add($create);

        $view = $auth->createPermission('auth/post/view');
        $view->description = 'view post';
        $auth->add($view);

        $update = $auth->createPermission('auth/post/update');
        $update->description = 'update post';
        $auth->add($update);

        $delete = $auth->createPermission('auth/post/delete');
        $delete->description = 'delete post';
        $auth->add($delete);

//        // add "author" role and give this role the "createPost" permission
//        $author = $auth->createRole('author');
//        $auth->add($author);
//        $auth->addChild($author, $createPost);
//
//        // add "admin" role and give this role the "updatePost" permission
//        // as well as the permissions of the "author" role
//        $admin = $auth->createRole('admin');
//        $auth->add($admin);
//        $auth->addChild($admin, $updatePost);
//        $auth->addChild($admin, $author);
//
//        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
//        // usually implemented in your User model.
//        $auth->assign($author, 2);
//        $auth->assign($admin, 1);

        return "salam";
    }
}