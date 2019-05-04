<?php
namespace GlobalsProjects\InvoiceAdjustment\Http\Controllers;

use Illuminate\Http\Request;
use GlobalsProjects\InvoiceAdjustment\Library\InvoiceUtility;

class InvoiceAdjustmentController
{
    /**
     * @var InvoiceUtility
     */
    private $invoiceUtility;

    public function __construct()
    {
        $invoiceClassName = config('invoiceadjustment.model.class');

        if (empty($invoiceClassName)) {
            die('Please define a invoice class for the Invoice Adjustment package');
        }

        $this->invoiceUtility = new InvoiceUtility(new $invoiceClassName);
    }

    /**
     * Returns the current configuration to be used in the
     * user interface
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function config()
    {
        return response()
            ->json([
                'config' => config('invoiceadjustment'),
                'invoices' => $this->invoiceUtility->getAllInvoices(),
                'messages' => __('invoice-adjustment::tool')
            ]);
    }

    /**
     * Apply the invoice adjustment
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply(Request $request)
    {
        dd($request);
        $this->invoiceUtility->adjust();

        return response()
            ->json([
                'status' => 'okay!'
            ]);
    }
}