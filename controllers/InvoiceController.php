<?php

namespace app\controllers;

use Yii;
use app\models\Invoice;
use app\models\InvoiceSearch;
use app\models\Item;
use app\models\Party;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
     * Lists all Invoice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $queryCondition = ['invoice_id' => $id];
        $query = Item::find()->where($queryCondition);

        $itemsSubtotal = $query->sum('amount');
        $itemsTax = $itemsSubtotal * 0.1;
        $itemsPayments = ($itemsSubtotal + $itemsTax) * -1;

        $itemsDataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'itemsDataProvider' => $itemsDataProvider,
            'itemsSubtotal' => $itemsSubtotal,
            'itemsTax' => $itemsTax,
            'itemsPayments' => $itemsPayments,
        ]);
    }

    /**
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Invoice();
        $partiesForDropDown = $this->getParties();     

        if ($this->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $post = $this->request->post();
                $post['Item'] = array_values($post['Item']);

                if ($model->load($post, 'Invoice') && $model->save()) {              

                    foreach ($post['Item'] as $item) {
                        $modelItem = $this->createNewItem($model->id, $item);
                        
                        if (!$modelItem->save()) {
                            Yii::$app->session->setFlash('error', $modelItem->getErrorSummary(true));
                            return $this->render('create', [
                                'model' => $model,
                                'partiesForDropDown' => $partiesForDropDown
                            ]);
                        }
                    }

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{                 
                    Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                }

            } catch (\Throwable $th) {
                $transaction->rollBack();
                throw $th;
            }      
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'partiesForDropDown' => $partiesForDropDown,
        ]);
    }

    /**
     * Updates an existing Invoice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $partiesForDropDown = $this->getParties();

        $itemsFromDb = Item::findAll(['invoice_id' => $id]);

        if ($this->request->isPost) {
            $transaction = $model->getDb()->beginTransaction();
            try {
                $post = $this->request->post();
                $post['Item'] = array_values($post['Item']);

                if ($model->load($post, 'Invoice') && $model->save()) { 
                    Item::deleteAll(['invoice_id' => $model->id]);

                    foreach ($post['Item'] as $item) {
                        $modelItem = $this->createNewItem($model->id, $item);

                        if (!$modelItem->save()) {
                            Yii::$app->session->setFlash('error', $modelItem->getErrorSummary(true));
                            return $this->render('update', [
                                'model' => $model,
                                'partiesForDropDown' => $partiesForDropDown,
                                'itemsFromDb' => $itemsFromDb,
                            ]);
                        }
                    }

                    $transaction->commit();
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else{                 
                    Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
                }

            } catch (\Throwable $th) {
                $transaction->rollBack();
                throw $th;
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'partiesForDropDown' => $partiesForDropDown,
            'itemsFromDb' => $itemsFromDb,
        ]);
    }

    /**
     * Deletes an existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function getParties()
    {
        $parties = Party::find()->all();
        return array('' => 'Select a party') + ArrayHelper::map($parties, 'id', 'party_name');
    }

    private function createNewItem(int $invoiceId, $item) 
    {
        $modelItem = new Item();
        $modelItem->invoice_id = $invoiceId;
        $modelItem->amount = $item['amount'];
        $modelItem->item_type = $item['item_type'];
        $modelItem->description = $item['description'];
        $modelItem->quantity = $item['quantity'];
        $modelItem->unit_price = $item['unit_price'];

        return $modelItem;
    }
}
