<?php 

namespace app\commands;

use yii\console\Controller;
use app\models\TblUser;
use Yii;

class TblUserController extends Controller
{
	public function actionLoadUser()
	{
		TblUser::deleteAll();

		$tblUserData = [
			[
				'username' => 'vasya',
				'password' => 'pass',
				'name' => 'Вася',
				'phone' => '222-22-22',
				'email' => 'vasya@gmail.com',
				'role_id' => 1,
				'activation_status' => true,
			],
			[
				'username' => 'petya',
				'password' => 'parol',
				'name' => 'Петя',
				'phone' => '333-33-33',
				'email' => 'petya@mail.ru',
				'role_id' => 2,
				'activation_status' => true,
			],
			[
				'username' => 'john',
				'password' => '1234',
				'name' => 'John',
				'phone' => '111-11-11',
				'email' => 'john@yandex.ru',
				'role_id' => 2,
				'activation_status' => true,
			],
		];

		foreach ($tblUserData as $data) {
			$user = new TblUser($data);
			$user->hashPassword = true;
			$user->save();
		}
	}	
	
	public function actionPermissions()
	{
		$auth = Yii::$app->authManager;
		
		$updateUser = $auth->createPermission('updateUser');
		$updateUser->description = 'Update a user';
		$auth->add($updateUser);

		$deleteUser = $auth->createPermission('deleteUser');
		$deleteUser->description = 'Delete a user';
		$auth->add($deleteUser);
	}

    public function actionRoles()
    {
        $auth = Yii::$app->authManager;

        $updateUser = $auth->getPermission('updateUser');
        $deleteUser = $auth->getPermission('deleteUser');

        $member = $auth->createRole('member');
        $auth->add($member);
        $auth->addChild($member, $updateUser);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $deleteUser);
        $auth->addChild($admin, $member);
    }

    public function actionRules()
    {
        $auth = Yii::$app->authManager;
    	
    	$rule = new ProfileRule();
    	$auth->add($rule);

    	$updateUser = $auth->getPermission('updateUser');
    	$updateUser->ruleName = $rule->name;
    	$auth->update('updateUser', $updateUser);	
    }

}

?>