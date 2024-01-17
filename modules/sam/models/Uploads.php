<?php

namespace app\modules\sam\models;

use Yii;

/**
 * This is the model class for table "uploads".
 *
 * @property int $upload_id
 * @property string|null $ref Ref
 * @property string|null $file_name ชื่อไฟล์
 * @property string|null $real_filename ชื่อไฟล์จริง
 * @property string|null $created_at วันที่บันทึก
 * @property string|null $extension ประเภท
 */
class Uploads extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'uploads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['ref', 'extension'], 'string', 'max' => 50],
            [['file_name', 'real_filename'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'upload_id' => Yii::t('app', 'Upload ID'),
            'ref' => Yii::t('app', 'Ref'),
            'file_name' => Yii::t('app', 'ชื่อไฟล์'),
            'real_filename' => Yii::t('app', 'ชื่อไฟล์จริง'),
            'created_at' => Yii::t('app', 'วันที่บันทึก'),
            'extension' => Yii::t('app', 'ประเภท'),
        ];
    }
}
