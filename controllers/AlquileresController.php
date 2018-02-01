<?php

namespace app\controllers;

use app\models\Alquileres;
use app\models\AlquileresSearch;
use app\models\GestionarPeliculaForm;
use app\models\GestionarSocioForm;
use app\models\Peliculas;
use app\models\Socios;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id !== 'devolver') {
            Yii::$app->session->set('rutaVuelta', Url::to());
        }
        return parent::beforeAction($action);
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
            $gestionarPeliculaForm = new GestionarPeliculaForm([
                'numero' => $numero,
                'codigo' => $codigo,
            ]);
            Yii::$app->response->format = Response::FORMAT_JSON;
            return \yii\widgets\ActiveForm::validate($gestionarSocioForm, $gestionarPeliculaForm);
        }

        $data = [];

        if ($numero !== null && $gestionarSocioForm->validate()) {
            $data['socio'] = Socios::findOne(['numero' => $gestionarSocioForm->numero]);
            $gestionarPeliculaForm = new GestionarPeliculaForm([
                'numero' => $gestionarSocioForm->numero,
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
