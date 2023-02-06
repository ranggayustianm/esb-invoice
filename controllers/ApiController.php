<?php

namespace app\controllers;

use yii\rest\Controller;
use app\models\Invoice;
use app\models\Party;
use DateTime;
use yii\web\NotFoundHttpException;

class ApiController extends Controller
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
        $invoiceItems = $invoice->getItems()->all();

        $subtotal = 0;
        $tax10Percent = 0;
        $payments = 0;
        foreach ($invoiceItems as $invoiceItem) {
            $subtotal += $invoiceItem->amount;
        }
        $tax10Percent = $subtotal * 0.1;
        $payments = ($subtotal + $tax10Percent) * -1;

        $invoiceToBeReturned = $invoice->toArray();
        $invoiceToBeReturned['items'] = $invoiceItems;
        $invoiceToBeReturned['subtotal'] = $subtotal;
        $invoiceToBeReturned['tax10Percent'] = $tax10Percent;
        $invoiceToBeReturned['payments'] = $payments;

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