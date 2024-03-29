<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\modules\sam\models\Employee $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'docs:ntext',
            'images:ntext',
            'ref',
            [
                'label' => Yii::t('app', 'Images'),
                'format' => 'raw',
                'value' => function ($model) {
                    return dosamigos\gallery\Gallery::widget(['items' => $model->getThumbnails($model->ref)]);
                },
            ],
            [
                'label' => Yii::t('app', 'Docs'),
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->getImageCover($model->ref);
                },
            ],

            [
                'attribute' => 'docs',
                'format' => 'html',
                'value' => $model->listDownloadFiles('docs')
            ],
        ],
    ]) ?>

</div>
