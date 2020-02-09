<?php

namespace soless\poll\controllers;

use soless\poll\models\PsPollItemHit;
use soless\poll\models\VoteForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \dektrium\user\traits\AjaxValidationTrait;

/**
 * PollController implements the CRUD actions for PsPoll model.
 */
class PollController extends \yii\rest\Controller
{
    use AjaxValidationTrait;

    /**
     * Lists all PsPoll models.
     * @return mixed
     */
    public function actionVote()
    {
        $form = new VoteForm();
        $form->load(Yii::$app->request->post());
        /** @var PsPollItemHit $model */
        $model = $form->prepareVote();
        $success = $model->save();

        return [
            'success' => $success,
            'results' => $success ? $model->psPoll->results : [],
            'errors' => $model->errors,
        ];
    }

}
