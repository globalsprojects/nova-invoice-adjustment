<?php
namespace GlobalsProjects\InvoiceAdjustment\Library;

use Illuminate\Http\Request;

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

        $this->model->with(['rectifieds', 'ancestor', 'tickets.streaming', 'tickets.discount'])->each(function($invoice) use ($invoiceCollection) {
            if (!$invoice->ancestor) {
                $invoiceCollection->push($invoice);
            }
        });

        return $invoiceCollection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function adjust(Request $request)
    {
        $invoice = $this->model->findOrFail($request->id);
        $tickets = collect([]);

        $invoice->tickets->each(function($ticket) use ($request, $tickets) {
            if (in_array($ticket->id, array_map(function($adjustedTicket) {
                return $adjustedTicket['id'];
            }, $request->changes['tickets']))) {
                $ticket->invalidated_at = now();
                $ticket->save();
                $adjustedTicket = $ticket->replicate();
                $adjustedTicket->price *= -1;
                $tickets->push($adjustedTicket);
            }
        });

        $adjustedInvoice = $invoice->replicate();
        $adjustedInvoice->amount = $request->changes['amount'];
        $adjustedInvoice->reference = $this->model->generateReference();
        $adjustedInvoice->push();
        $adjustedInvoice->tickets()->saveMany($tickets);
        $adjustedInvoice->ancestor()->associate($invoice);
        $adjustedInvoice->save();
    }

}