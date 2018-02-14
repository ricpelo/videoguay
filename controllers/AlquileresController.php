<?php

namespace app\controllers;

use app\models\Alquileres;
use app\models\AlquileresSearch;
use app\models\GestionarPeliculaForm;
use app\models\GestionarSocioForm;
use app\models\Peliculas;
use app\models\Socios;
use app\models\Usuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/**
 * AlquileresController implements the CRUD actions for Alquileres model.
 */
class AlquileresController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['gestionar', 'index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['gestionar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Usuarios::getPermitido();
                        },
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id !== 'devolver') {
            Yii::$app->session->set('rutaVuelta', Url::to());
        }
        return parent::beforeAction($action);
    }

    public function actionPendientes($numero)
    {
        if (($socio = Socios::findOne(['numero' => $numero])) === null) {
            return '';
        }

        $pendientes = $socio->getPendientes()->with('pelicula');

        return $this->renderAjax('pendientes', [
            'pendientes' => $pendientes,
        ]);
    }

    public function actionGestionarAjax($numero = null, $codigo = null)
    {
        $gestionarPeliculaForm = new GestionarPeliculaForm([
            'numero' => $numero,
            'codigo' => $codigo,
        ]);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($gestionarPeliculaForm);
        }

        return $this->render('gestionar-ajax', [
            'gestionarPeliculaForm' => $gestionarPeliculaForm,
        ]);
    }

    /**
     * Alquila y devuelve películas en una sola acción.
     * @return mixed
     * @param null|mixed $numero
     * @param null|mixed $codigo
     */
    public function actionGestionar($numero = null, $codigo = null)
    {
        $gestionarSocioForm = new GestionarSocioForm([
            'numero' => $numero,
        ]);

        if (Yii::$app->request->isAjax) {
            if ($gestionarSocioForm->load(Yii::$app->request->queryParams)) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $gestionarPeliculaForm = new GestionarPeliculaForm();
                if ($gestionarPeliculaForm->load(Yii::$app->request->queryParams)) {
                    return ActiveForm::validate($gestionarSocioForm, $gestionarPeliculaForm);
                }
                return ActiveForm::validate($gestionarSocioForm);
            }
        }

        $data = [];

        if ($numero !== null && $gestionarSocioForm->validate()) {
            $data['socio'] = Socios::findOne(['numero' => $gestionarSocioForm->numero]);
            $gestionarPeliculaForm = new GestionarPeliculaForm([
                'numero' => $numero,
                'codigo' => $codigo,
            ]);
            $data['gestionarPeliculaForm'] = $gestionarPeliculaForm;
            if ($codigo !== null && $gestionarPeliculaForm->validate()) {
                $data['pelicula'] = Peliculas::findOne([
                    'codigo' => $gestionarPeliculaForm->codigo,
                ]);
            }
        }

        $data['gestionarSocioForm'] = $gestionarSocioForm;
        return $this->render('gestionar', $data);
    }

    public function actionAlquilarAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $socio = Socios::findOne(['numero' => Yii::$app->request->post('numero')]);
        $pelicula = Peliculas::findOne(['codigo' => Yii::$app->request->post('codigo')]);
        $alquiler = new Alquileres([
            'socio_id' => $socio->id,
            'pelicula_id' => $pelicula->id,
        ]);
        if ($alquiler->validate()) {
            $alquiler->save();
            return true;
        }
        return false;
    }

    /**
     * Alquila una película dados `socio_id` y `pelicula_id`
     * pasados por POST.
     * @param  string   $numero        El número del socio para volver a él.
     * @return Response                La redirección.
     * @throws BadRequestHttpException Si algún `id` es incorrecto.
     */
    public function actionAlquilar($numero)
    {
        $alquiler = new Alquileres();

        if ($alquiler->load(Yii::$app->request->post(), '') &&
            $alquiler->save()) {
            return $this->redirect([
                'alquileres/gestionar',
                'numero' => $numero,
            ]);
        }

        throw new BadRequestHttpException('No se ha creado el alquiler.');
    }

    public function actionDevolverAjax()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id = Yii::$app->request->post('id');

        if (($alquiler = Alquileres::findOne($id)) === null) {
            throw new NotFoundHttpException('El alquiler no existe.');
        }

        $alquiler->devolucion = date('Y-m-d H:i:s');
        return $alquiler->save();
    }

    /**
     * Devuelve un alquiler indicado por el `id` pasado por POST.
     * @param  string   $numero      El número del socio para volver a él.
     * @return Response              La redirección.
     * @throws NotFoundHttpException Si el `id` falta o no es correcto.
     */
    public function actionDevolver($numero)
    {
        if (($id = Yii::$app->request->post('id')) === null) {
            throw new NotFoundHttpException('Falta el alquiler.');
        }

        if (($alquiler = Alquileres::findOne($id)) === null) {
            throw new NotFoundHttpException('El alquiler no existe.');
        }

        $alquiler->devolucion = date('Y-m-d H:i:s');
        $alquiler->save();

        $url = Yii::$app->session->get(
            'rutaVuelta',
            ['alquileres/gestionar', 'numero' => $numero]
        );
        Yii::$app->session->remove('rutaVuelta');

        return $this->redirect($url);
    }

    /**
     * Lists all Alquileres models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlquileresSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Muestra un listado de alquileres.
     * @return mixed
     */
    public function actionListado()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Alquileres::find()->joinWith(['socio', 'pelicula']),
        ]);

        $dataProvider->sort->attributes['socio.numero'] = [
            'asc' => ['socios.numero' => SORT_ASC],
            'desc' => ['socios.numero' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['pelicula.codigo'] = [
            'asc' => ['peliculas.codigo' => SORT_ASC],
            'desc' => ['peliculas.codigo' => SORT_DESC],
        ];

        return $this->render('listado', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alquileres model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Alquileres model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Alquileres();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Alquileres model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Alquileres model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Alquileres model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Alquileres the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alquileres::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
