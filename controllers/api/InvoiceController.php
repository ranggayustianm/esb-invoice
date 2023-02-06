<?php

namespace app\controllers\api;

use yii\rest\Controller;
use app\models\Invoice;
use app\models\Party;
use DateTime;
use yii\web\NotFoundHttpException;

class InvoiceController extends Controller
{

    public function actionIndex()
    {
        $invoices = Invoice::find()->all();

        $invoicesCount = count($invoices);
        for ($i=0; $i < $invoicesCount; $i++) { 
            $this->prepareInvoiceOutput($invoices[$i]);
        }

        return $invoices;
    }

    public function actionView($id)
    {
        $invoice = Invoice::findOne($id);
        if(is_null($invoice)){
            throw new NotFoundHttpException("Invoice $id is not found on the database", 1);          
        }
        
        $this->prepareInvoiceOutput($invoice);
        $invoiceItemsQuery = $invoice->getItems();
        $invoiceItems = $invoiceItemsQuery->all();

        $itemsSubtotal = (double)$invoiceItemsQuery->sum('amount');
        $itemsTax = $itemsSubtotal * 0.1;
        $itemsPayments = ($itemsSubtotal + $itemsTax) * -1;

        $invoiceToBeReturned = $invoice->toArray();
        $invoiceToBeReturned['items'] = $invoiceItems;
        $invoiceToBeReturned['subtotal'] = $itemsSubtotal;
        $invoiceToBeReturned['tax'] = $itemsTax;
        $invoiceToBeReturned['payments'] = $itemsPayments;

        return $invoiceToBeReturned;
    }

    private function prepareInvoiceOutput(Invoice &$invoice)
    {
        $invoice['issue_date'] = date_format(new DateTime($invoice['issue_date']), 'd F Y');
        $invoice['due_date'] = date_format(new DateTime($invoice['due_date']), 'd F Y');
        $invoice['is_paid'] = (boolean)$invoice['is_paid'];
        $invoice['party_from'] = Party::findOne($invoice['party_from'])->party_name;
        $invoice['party_to'] = Party::findOne($invoice['party_to'])->party_name;
    }

}