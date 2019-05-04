<?php
namespace GlobalsProjects\InvoiceAdjustment\Library;

class InvoiceUtility
{

    private $model;

    /**
     * InvoiceUtility constructor.
     * @param Model|mixed $invoiceClass
     */
    public function __construct($invoiceClass)
    {
        $this->model = new $invoiceClass;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAllInvoices()
    {
        $invoiceCollection = collect([]);

        $this->model->with(['tickets.streaming', 'tickets.discount'])->each(function($invoice) use ($invoiceCollection) {
            $invoiceCollection->push($invoice);
        });

        return $invoiceCollection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function adjust($invoice)
    {
        dd($invoice);
    }

}