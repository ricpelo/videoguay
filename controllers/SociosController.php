<?php

namespace app\controllers;

use app\models\Alquileres;
use app\models\Socios;
use app\models\SociosSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SociosController implements the CRUD actions for Socios model.
 */
class SociosController extends Controller
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
        Yii::$app->session->set('rutaVuelta', Url::to());
        return parent::beforeAction($action);
    }

    /**
     * Lists all Socios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SociosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Socios model.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $p = Alquileres::find()
            ->with('pelicula')
            ->where(['socio_id' => $id])
            ->orderBy('created_at DESC')
            ->limit(10)
            ->all();

        Yii::$app->session->set('rutaVuelta', Url::to());

        return $this->render('view', [
            'model' => $this->findModel($id),
            'alquileres' => $p,
        ]);
    }

    /**
     * Creates a new Socios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Socios();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Socios model.
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
     * Deletes an existing Socios model.
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

    public function actionAjax($numero)
    {
        if (!Yii::$app->request->isAjax) {
            return '';
        }
        if (($socio = Socios::findOne(['numero' => $numero])) === null) {
            return '';
        }
        return \yii\widgets\DetailView::widget([
            'model' => $socio,
            'attributes' => [
                'nombre',
                'telefono',
            ],
        ]);
    }
    /**
     * Finds the Socios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Socios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Socios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
