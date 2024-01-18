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

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>


    <div class="col-md-12">
        <div class="form-group field-upload_files">
            <label class="control-label" for="upload_files[]"> <?= Yii::t('app', 'Images') ?> </label>
            <div>
                <?= FileInput::widget([
                    'name' => 'upload_ajax[]',
                    'language' => Yii::$app->language == 'th-TH' ? 'th' : 'en',
                    'options' => ['multiple' => true], //'accept' => 'image/*' หากต้องเฉพาะ image
                    'pluginOptions' => [
                        'initialPreview' => $initialPreview,
                        'initialPreviewConfig' => $initialPreviewConfig,
                        'previewFileType' => 'any',
                        'uploadUrl' => Url::to(['/sam/employee/upload-ajax']),
                        'showCancel' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        // 'browseIcon' => '<i class="fas fa-folder"></i> ',
                        // 'browseLabel' =>   Yii::t('app', 'Select...'),
                        'overwriteInitial' => false,
                        'initialPreviewShowDelete' => true,
                        'uploadExtraData' => [
                            'ref' => $model->ref,
                        ],
                        'maxFileCount' => 10,
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'ref')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>