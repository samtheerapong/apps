<?php

namespace app\modules\sam\controllers;

use app\modules\sam\models\Employee;
use app\modules\sam\models\search\EmployeeSearch;
use app\modules\sam\models\Uploads;
use Exception;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Employee models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Employee();


        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $this->Uploads(false);

                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->ref = substr(Yii::$app->getSecurity()->generateRandomString(), 10);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        list($initialPreview, $initialPreviewConfig) = $this->getInitialPreview($model->ref);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $this->Uploads(false);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'initialPreview' => $initialPreview,
            'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->removeUploadDir($model->ref);
        Uploads::deleteAll(['ref' => $model->ref]);

        $model->delete();

        return $this->redirect(['index']);
    }


    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    // Uploads
    public function actionUploadAjax()
    {
        $this->Uploads(true);
    }

    private function CreateDir($folderName)
    {
        if ($folderName != NULL) {
            $basePath = Employee::getUploadPath();
            if (BaseFileHelper::createDirectory($basePath . $folderName, 0777)) {
                BaseFileHelper::createDirectory($basePath . $folderName . '/thumbnail', 0777);
            }
        }
        return;
    }

    private function Uploads($isAjax = false)
    {
        if (Yii::$app->request->isPost) {
            $uploadAjax = UploadedFile::getInstancesByName('upload_ajax');
            if ($uploadAjax) {
                if ($isAjax === true) {
                    $ref = Yii::$app->request->post('ref');
                } else {
                    $ReqPost = Yii::$app->request->post('Employee');
                    $ref = $ReqPost['ref'];
                }

                $this->CreateDir($ref);

                foreach ($uploadAjax as $file) {
                    $fileName       = $file->baseName . '.' . $file->extension;
                    $realFileName   = md5($file->baseName . time()) . '.' . $file->extension;
                    $savePath       = Employee::UPLOAD_FOLDER . '/' . $ref . '/' . $realFileName;
                    if ($file->saveAs($savePath)) {

                        if ($this->isImage(Url::base(true) . '/' . $savePath)) {
                            $this->createThumbnail($ref, $realFileName);
                        }

                        $model                  = new Uploads();
                        $model->ref             = $ref;
                        $model->file_name       = $fileName;
                        $model->real_filename   = $realFileName;
                        $model->save();

                        if ($isAjax === true) {
                            echo json_encode(['success' => 'true']);
                        }
                    } else {
                        if ($isAjax === true) {
                            echo json_encode(['success' => 'false', 'eror' => $file->error]);
                        }
                    }
                }
            }
        }
    }

    private function getInitialPreview($ref)
    {
        $datas = Uploads::find()->where(['ref' => $ref])->all();
        $initialPreview = [];
        $initialPreviewConfig = [];
        foreach ($datas as $key => $value) {
            array_push($initialPreview, $this->getTemplatePreview($value));
            array_push($initialPreviewConfig, [
                'caption' => $value->file_name,
                'width'  => '120px',
                'url'    => Url::to(['/sam/employee/deletefile-ajax']),
                'key'    => $value->upload_id
            ]);
        }
        return  [$initialPreview, $initialPreviewConfig];
    }

    

    public function isImage($filePath)
    {
        return @is_array(getimagesize($filePath)) ? true : false;
    }

    private function getTemplatePreview(Uploads $model)
    {
        $filePath = Employee::getUploadUrl() . $model->ref . '/thumbnail/' . $model->real_filename;
        $isImage  = $this->isImage($filePath);
        if ($isImage) {
            $file = Html::img($filePath, ['class' => 'file-preview-image', 'alt' => $model->file_name, 'title' => $model->file_name]);
        } else {
            $file =     "<div class='file-preview-other'> " .
                        "<h2><i class='glyphicon glyphicon-file'></i></h2>" .
                        "</div>";
        }
        return $file;
    }

    public function createThumbnail($folderName, $fileName, $width = 300, $quality = 80)
    {
        $uploadPath = Employee::getUploadPath() . '/' . $folderName . '/';
        $file = $uploadPath . $fileName;

        $image = Yii::$app->image->load($file);
        $image->resize($width);

        $thumbnailPath = $uploadPath . 'thumbnail/' . $fileName;
        $image->save($thumbnailPath, ['quality' => $quality]);

        return;
    }

    public function actionDeletefileAjax()
    {
        $model = Uploads::findOne(Yii::$app->request->post('key'));
        if ($model !== NULL) {
            $filename  = Employee::getUploadPath() . $model->ref . '/' . $model->real_filename;
            $thumbnail = Employee::getUploadPath() . $model->ref . '/thumbnail/' . $model->real_filename;
            if ($model->delete()) {
                @unlink($filename);
                @unlink($thumbnail);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
