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

        $this->model->each(function($invoice) use ($invoiceCollection) {
            $invoiceCollection->push($invoice);
        });

        return $invoiceCollection;
    }

}