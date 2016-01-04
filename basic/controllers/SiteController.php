<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\EntryForm;
use app\models\ContactForm;
use app\models\TblUser;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
            
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

    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    // public function actionAbout()
    // {
    //     return $this->render('about');
    // }


        // public function actionEntry(){
    //     $model = new EntryForm();
    //     if($model->load(Yii::$app->request->post()) 
    //         &&
    //         $model->validate())
    //     {
    //         return $this->render('entry-confirm', ['model' => $model]);
    //     }else{
    //         return $this->render('entry', ['model' => $model]);
    //     }
    // }


}
