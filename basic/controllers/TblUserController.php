<?php

namespace app\controllers;

use Yii;
use app\models\TblUser;
use app\models\TblUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;

/**
 * TblUserController implements the CRUD actions for TblUser model.
 */
class TblUserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post'],
                ],
            ],
 
            'access' => [
                'class' => AccessControl::className(),
                'only' => [/*'index', */'update', 'delete'],  
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }



    /**
     * Lists all TblUser models.
     * @return mixed
     */
    public function actionAdmin()
    {
        $searchModel = new TblUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionLogin()
    {
        
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new TblUser(['scenario' => TblUser::SCENARIO_LOGIN]);
            
        if ($model->load(Yii::$app->request->post()) && 
            $model->login()) {

            // Присвоил роль здесь. Сначала хотел в модели TblUser aftersave. Теперь чтобы изменения вступили в силу, нужно перелогиниться. 
            $auth = Yii::$app->authManager;
            $role = ($model->getUser()->role_id === 1) ? $auth->getRole('admin') : $auth->getRole('user');
            $auth->revokeAll($model->getUser()->id);
            $auth->assign($role, $model->getUser()->id);

            return $this->goBack();
        } else if($model->load(Yii::$app->request->post())){
            Yii::$app->session->setFlash('error', TblUser::ACTIVATION_REQUIRED);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }



    public function actionActivation()
    {
        //check activation key
        $id = abs(+Yii::$app->request->get('id'));
        $trialHash = trim(strip_tags(Yii::$app->request->get('activation_key')));
        $model = $this->findModel($id);

        if(
            !$model->activation_status && 
            $model->activation_key === $trialHash
            )
        {
            $model->activation_status = true;
            $model->save();

            return $this->redirect(['site/login']);

        } else {
            Yii::$app->session->setFlash('error', TblUser::ACTIVATION_FAILED);
            return $this->redirect(['tbl-user/create']);
        }

    }

    /**
     * Displays a single TblUser model.
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
     * Creates a new TblUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        // Лучше через сценарий, чем заводить отдельную модель
        $model = new TblUser(['scenario' => TblUser::SCENARIO_CREATE]);
        $model->hashPassword = true;
        $model->activation_status = false;

        // Присваиваем роль по умолчанию
        $model->role_id = 2;
        $model->activation_key = sha1(time() . $model->username);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $activationUrl = Yii::$app->urlManager->createAbsoluteUrl(
                [
                    'tbl-user/activation', 
                    'id' => $model->id,
                    'activation_key' => $model->activation_key,
                ]
            );

            Yii::$app->mailer->compose('html', ['msg' => $activationUrl])
                 ->setFrom('test.golden@mail.ru')
                 ->setTo($model->email)
                 ->setSubject('Yii2 account activation')
                 ->send();

            return $this->redirect(['site/login']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TblUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(!Yii::$app->user->can('updateUser')){
            Yii::$app->session->setFlash('error', TblUser::NOT_ENOUGH_RIGHTS_MESSAGE);
            return $this->redirect(['index']);
        }
                
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
        
    }

    /**
     * Deletes an existing TblUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->user->can('deleteUser')){
            Yii::$app->session->setFlash('error', TblUser::NOT_ENOUGH_RIGHTS_MESSAGE);
            return $this->redirect(['index']);
        }                

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TblUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TblUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TblUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
