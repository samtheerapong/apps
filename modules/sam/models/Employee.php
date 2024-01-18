<?php

namespace app\modules\sam\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "employee".
 *
 * @property int $id
 * @property string|null $docs Documents
 * @property string|null $images Images
 * @property string|null $ref Ref.
 */
class Employee extends \yii\db\ActiveRecord
{

    const UPLOAD_FOLDER = 'uploads/employee';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ref'], 'string', 'max' => 100],
            [['docs', 'images'], 'file', 'maxFiles' => 10, 'skipOnEmpty' => true]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'docs' => Yii::t('app', 'Documents'),
            'images' => Yii::t('app', 'Images'),
            'ref' => Yii::t('app', 'Ref.'),
        ];
    }

    // uploading img
    public static function getUploadPath()
    {
        return Yii::getAlias('@webroot') . '/' . self::UPLOAD_FOLDER . '/';
    }

    public static function getUploadUrl()
    {
        return Url::base(true) . '/' . self::UPLOAD_FOLDER . '/';
    }

    public function getThumbnails($ref)
    {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->all();
        $preview = [];
        foreach ($uploadFiles as $file) {
            $preview[] = [
                'url' => self::getUploadUrl(true) . $ref . '/' . $file->real_filename,
                'src' => self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename,
                'options' => [
                    'title' => $ref,
                    // 'style' => 'width: 300px; height: 300px;', // Adjust as needed
                ],
            ];
        }
        return $preview;
    }

    


    public function getImageCover($ref)
    {
        $uploadFiles = Uploads::find()->where(['ref' => $ref])->all();
        $thumbnails = [];

        foreach ($uploadFiles as $file) {
            $thumbnails[] = [
                'url' => self::getUploadUrl(true) . $ref . '/' . $file->real_filename,
                'src' => self::getUploadUrl(true) . $ref . '/thumbnail/' . $file->real_filename,
                'options' => [
                    'title' => $file->ref,
                ],
            ];
        }

        if (!empty($thumbnails)) {
            $thumbnailSrc = $thumbnails[0]['src'];
        } else {
            $thumbnailSrc = Yii::getAlias('@web') . '/uploads/no-image.jpg';
        }

        return Html::a(Html::img($thumbnailSrc, ['height' => '80px', 'class' => 'img-rounded']), ['view', 'id' => $this->id]);

        // เรียกใช้งาน echo $model->getImageView($model->ref);
    }
    // End uploading img


}
