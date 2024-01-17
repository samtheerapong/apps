<?php

use yii\helpers\Html;
use app\models\User;
use app\modules\ncr\models\NcrConcession;
use app\modules\ncr\models\NcrSolvingType;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\ncr\models\NcrSolving $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ncr-solving-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ncr_status_id')->hiddenInput(['value' => 2])->label(false) ?>

    <div class="card border-secondary">
        <div class="card-header text-white bg-secondary">
            <?= Yii::t('app', 'Corrective action'); ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'solving_type_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(NcrSolvingType::find()->where(['active' => 1])->all(), 'id', 'type_name'),
                        'options' => ['placeholder' => Yii::t('app', 'Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-1">
                    <?= $form->field($solvingModel, 'quantity')->textInput(['type' => 'number',]) ?>
                </div>

                <div class="col-md-2">
                    <?= $form->field($solvingModel, 'unit')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'operation_date')->widget(
                        DatePicker::class,
                        [
                            'options' => ['placeholder' => Yii::t('app', 'Select...'), 'required' => true],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ]
                        ]
                    ); ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'operation_name')->widget(Select2::class, [
                        'data' => ArrayHelper::map(User::find()->where(['rule_id' => 8])->all(), 'id', 'thai_name'),
                        'options' => ['placeholder' => Yii::t('app', 'Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($solvingModel, 'proceed')->textInput(['maxlength' => true]) ?>
                </div>


            </div>
        </div>

        <div class="card-header text-white bg-secondary">
            <?= Yii::t('app', 'Special acceptance'); ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'ncr_concession_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(NcrConcession::find()->where(['active' => 1])->all(), 'id', 'concession_name'),
                        'options' => ['placeholder' => Yii::t('app', 'Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'customer_name')->textInput(['value' => $model->customer_name]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($solvingModel, 'process')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($solvingModel, 'cause')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'approve_name')->widget(Select2::class, [
                        'data' => ArrayHelper::map(User::find()->where(['rule_id' => 8])->all(), 'id', 'thai_name'),
                        'options' => ['placeholder' => Yii::t('app', 'Select...')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-3">
                    <?= $form->field($solvingModel, 'approve_date')->widget(
                        DatePicker::class,
                        [
                            'options' => ['placeholder' => Yii::t('app', 'Select...')],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ]
                        ]
                    ); ?>
                </div>
            </div>
        </div>
        <div class="card-header text-white bg-secondary">
            <?= Yii::t('app', 'Attach files'); ?>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">

                </div>
            </div>
        </div>

        <div class="card-footer">
            <div class="form-group">
                <div class="d-grid">
                    <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>