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
        $model->ref = substr(Yii::$app->getSecurity()->generateRandomString(), 10);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $this->CreateDir($model->ref);
                $model->docs = $this->uploadMultipleFile($model);

                if ($model->save()) {

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
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
        $this->findModel($id)->delete();

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

    //

     /***************** upload MultipleFile ******************/
     private function uploadMultipleFile($model, $tempFile = null)
     {
         $files = [];
         $json = '';
         $tempFile = Json::decode($tempFile);
         $UploadedFiles = UploadedFile::getInstances($model, 'docs');
         if ($UploadedFiles !== null) {
             foreach ($UploadedFiles as $file) {
                 try {
                     $oldFileName = $file->basename . '.' . $file->extension;
                     $newFileName = md5($file->basename . time()) . '.' . $file->extension;
                     $file->saveAs(Employee::UPLOAD_FOLDER . '/' . $model->ref . '/' . $newFileName);
                     $files[$newFileName] = $oldFileName;
                 } catch (Exception $e) {
                 }
             }
             $json = json::encode(ArrayHelper::merge($tempFile, $files));
         } else {
             $json = $tempFile;
         }
         return $json;
     }

     /***************** Create Dir ******************/
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
}
