<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\sam\models\Employee $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="form-group field-upload_files">
        <label class="control-label" for="docs[]"> ภาพถ่าย </label>
        <div>
            <?= FileInput::widget([
                'name' => 'docs[]',
                'options' => ['multiple' => true, 'accept' => 'image/*'], //'accept' => 'image/*' หากต้องเฉพาะ image
                'pluginOptions' => [
                    'overwriteInitial' => false,
                    'initialPreviewShowDelete' => true,
                    // 'initialPreview' => $initialPreview,
                    // 'initialPreviewConfig' => $initialPreviewConfig,
                    // 'uploadUrl' => Url::to(['upload-img']),
                    'uploadExtraData' => [
                        'ref' => $model->ref,
                    ],
                    'maxFileCount' => 10
                ]
            ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'ref')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>