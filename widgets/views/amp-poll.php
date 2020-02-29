<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $model \soless\poll\models\PsPoll */

\soless\poll\assets\amp\BindAsset::register($this);
\soless\poll\assets\amp\FormAsset::register($this);
\soless\poll\assets\amp\MustacheAsset::register($this);
?>

<amp-state id="poll_<?= $model->id ?>">
    "showOptions": 1
</amp-state>

<div class="clearfix">
    <div class="title-wrap">
        <h3><?= $model->title ?></h3>
    </div>
    <div class="description-wrap">
        <?= $model->description ?>
    </div>

    <?php if (!(!\Yii::$app->user->isGuest && in_array(\Yii::$app->user->id, $model->todayVotedUsers)) && !\Yii::$app->session->has('poll-'.$model->id)) : ?>
        <div class="options-wrap">

            <?php $formModel = new \soless\poll\models\VoteForm(); $formModel->pollId = $model->id; ?>
            <?php $form = ActiveForm::begin([
                'id' => 'poll-'. $model->id,
                'enableAjaxValidation' => false,
                'enableClientValidation' => false,
                'enableClientScript' => false,
                'method' => 'post',
                'options' => [
                    'action-xhr' => \Yii::$app->UrlManager->hostInfo . Url::toRoute(['/ps/api/vote']),
                    'target' => '_blank',
                ],
            ]); ?>

            <?= $form->field($formModel, 'pollId')->hiddenInput()->label(false) ?>

            <div class="radios-wrap" [class]="poll_<?= $model->id ?>.showOptions ? 'radios-wrap' : 'radios-wrap hide'">
                <?= $form->field($formModel, 'pollItemId')->radioList($model->optionsArray, [
                    'itemOptions' => [
                        'on' => 'change:'. 'poll-'. $model->id .'.submit,AMP.setState({poll_'. $model->id .': { showOptions: 0 } })'
                    ],
                ])->label(false) ?>
            </div>


            <div submit-success>
                <script type="text/plain"
                        template="amp-mustache">
              <p>{{caption}}</p>
              <p>Результаты</p>
              <p>Всего голосов: {{#totalVotes}}</p>
              <table>
                {{#data}}
                <tr>
                  <td>
                    {{title}}
                  </td>
                  <td>
                    {{count}}
                  </td>
                </tr>
                {{/data}}
              </table>
            </script>
            </div>
            <div submit-error>
                Что-то пошло не так и ваш голос не удалось сохранить... {{error}}
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    <?php else : ?>
        <div class="results-wrap">
            Results
        </div>
    <?php endif; ?>
</div>

