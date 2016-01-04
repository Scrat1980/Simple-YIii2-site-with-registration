<?php
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\rbac\rules\GroupRule;
/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * Initial RBAC action
     * @param integer $id ID
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Permissions

        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Update a user';
        $auth->add($updateUser);

        $deleteUser = $auth->createPermission('deleteUser');
        $deleteUser->description = 'Delete a user';
        $auth->add($deleteUser);

        $viewUser = $auth->createPermission('viewUser');
        $viewUser->description = 'View a user';
        $auth->add($viewUser);

        // Roles

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $viewUser);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $user);
        
        // Roles assignments
        // Присваиваем роли пользователям с id 55 и  53
        $auth->assign($user, 55);
        $auth->assign($admin, 53);

    }
}