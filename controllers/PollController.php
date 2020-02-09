<?php

namespace soless\poll\controllers;

use soless\poll\models\PsPollItemHit;
use soless\poll\models\VoteForm;
use Yii;
use soless\poll\models\PsPoll;
use soless\poll\models\PsPollSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \dektrium\user\traits\AjaxValidationTrait;

/**
 * PollController implements the CRUD actions for PsPoll model.
 */
class PollController extends Controller
{
    use AjaxValidationTrait;

    /**
     * {@inheritdoc}
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
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'view', ],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view', ],
                        'allow' => true,
                        'roles' => ['Administrator', 'PsPollAdmin', ],
                    ],
                ],
            ],
        ];
    }

    public function actionResults($id) {
        $model = PsPoll::findOne((int)$id);

        return $this->render('results', [
            'model' => $model,
        ]);
    }

    public function actionVote() {
        $form = new VoteForm();
        $form->load(Yii::$app->request->post());
        /** @var PsPollItemHit $model */
        $model = $form->prepareVote();
        $success = $model->save();

        if ($success) return $this->redirect('results', ['id' => $model->psPoll->id]);
        else throw new \yii\web\ServerErrorHttpException('Что-то пошло не так...');
    }

    /**
     * Lists all PsPoll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PsPollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PsPoll model.
     * @param integer $id
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
     * Creates a new PsPoll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PsPoll();
        $model->poll_up = date('Y-m-d H:i:s');
        $model->poll_down = date('Y-m-d H:i:s', strtotime('+1 month'));

        if ($model->load(Yii::$app->request->post())) {
            $this->performAjaxValidation($model);

            //$model->items = Yii::$app->request->post()['PsPollItem'] ?? null;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PsPoll model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->performAjaxValidation($model);

            //$model->items = Yii::$app->request->post()['PsPollItem'] ?? null;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PsPoll model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PsPoll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PsPoll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PsPoll::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
