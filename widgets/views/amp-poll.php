<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $model \soless\poll\models\PsPoll */

\soless\poll\assets\amp\BindAsset::register($this);
\soless\poll\assets\amp\FormAsset::register($this);
\soless\poll\assets\amp\MustacheAsset::register($this);
?>

<amp-state id="poll_<?= $model->id ?>">
    "showOptions": true,
    "submitDisabled": true
</amp-state>

<div class="clearfix poll-widget-wrap">
    <div class="title-wrap">
        <h3><?= $model->title ?></h3>
    </div>
    <div class="description-wrap">
        <?= $model->description ?>
    </div>

    <?php if (!(!\Yii::$app->user->isGuest && in_array(\Yii::$app->user->id, $model->todayVotedUsers)) && !\Yii::$app->session->has('poll-'.$model->id)) : ?>
        <div class="clearfix options-wrap">

            <?php $formModel = new \soless\poll\models\VoteForm(); $formModel->pollId = $model->id; ?>
            <?php $form = ActiveForm::begin([
                'id' => 'poll-'. $model->id,
                'action' => false,
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

            <div class="clearfix radios-wrap" [class]="poll_<?= $model->id ?>.showOptions ? 'radios-wrap' : 'radios-wrap hide'">
                <?= $form->field($formModel, 'pollItemId')->radioList($model->optionsArray, [
                    'itemOptions' => [
                        'tabindex' => 1,
                        'role' => 'button',
                        'on' => 'change:AMP.setState({poll_'. $model->id .': { showOptions: true, submitDisabled: false } })'
                    ],
                ])->label(false) ?>
            </div>

            <div class="clearfix submit-btn-wrap" [class]="poll_<?= $model->id ?>.showOptions ? 'clearfix submit-btn-wrap' : 'hide'">
                <input type="submit"
                       disabled
                       [disabled]="poll_<?= $model->id ?>.submitDisabled"
                       class="poll-submit block btn px2 py1"
                       value="Ответить"
                       on="tap:AMP.setState({poll_<?= $model->id ?>: { showOptions: false } })"
                >
            </div>


            <div submit-success>
                <script type="text/plain"
                        template="amp-mustache">
              <div class="caption-wrap">{{caption}}</div>
              <div class="results-wrap">
                      <div class="results-title">Результаты</div>
                      <div class="rows-wrap">
                        {{#data}}
                        <div class="row-wrap">
                            <div class="title-wrap">{{title}}</div>
                            <div class="proc-wrap">{{proc}}%</div>
                            <div class="count-wrap">{{count}}</div>
                            <div class="progressbar-wrap">
                                <div class="proc-progress w-{{proc}}"></div>
                            </div>
                        </div>
                        {{/data}}
                      </div>
                      <div class="total-votes-wrap">Всего голосов: {{totalVotes}}</div>
              </div>
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

