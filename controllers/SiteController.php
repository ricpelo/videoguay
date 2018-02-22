<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use Spatie\Dropbox\Exceptions\BadRequest;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
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

    /**
     * @inheritdoc
     */
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

    public function actionEmail()
    {
        $result = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo('josecarlos.arjona@iesdonana.org')
            ->setSubject('Este es un mensaje de prueba')
            ->setTextBody('Este es un mensaje de prueba que escribo para ver si llega el correo al Doñana desde el Yii2.')
            // ->setHtmlBody('<b>HTML content</b>')
            ->send();
        if (!$result) {
            // No se ha enviado correctamente
        }
        return 'Hecho';
    }

    /**
     * Displays homepage.
     *
     * @return string
     * @param mixed $socio_id
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDropbox()
    {
        $client = new \Spatie\Dropbox\Client(getenv('DROPBOX_TOKEN'));
        try {
            $client->delete('12.jpg');
        } catch (BadRequest $e) {
            // No se hace nada
        }
        $client->upload(
            '12.jpg',
            file_get_contents(Yii::getAlias('@uploads/12.jpg')),
            'overwrite'
        );
        $res = $client->createSharedLinkWithSettings('12.jpg', [
            'requested_visibility' => 'public',
        ]);
        return $res['url'];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
