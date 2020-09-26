<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Offer;
use App\OfferProduct;
use App\Invoice;
use App\InvoiceProduct;
use App\Customer;
use App\Company;
use App\Product;
use PDF;
use Auth;
use Illuminate\Support\Str;

class OverviewController extends Controller
{
    public function index(Request $request)
    {

        $year = $request->input('year');

        if (isset($year)) {
            $invoices = Invoice::whereYear('date', '=', $year)->get();
        } else {
            $invoices = Invoice::all();
        }


        return view(
            'overview.index',
            [
                'invoices' => $invoices,
                'invoiceTotalSumWithoutTax' => $this->getInvoiceTotalSumWithoutTax($invoices),
                'invoiceTotalSumTax' => $this->getInvoiceTotalSumTax($invoices),
                'invoiceTotalSum' => $this->getInvoiceTotalSum($invoices),
            ]
        );
    }

    /**
     * @param Invoice[] $invoices
     * @return float
     */
    private function getInvoiceTotalSumWithoutTax($invoices): float
    {
        $total = 0;
        foreach ($invoices as $invoice) {
            $total += $invoice->getSumTotalWithoutTaxAttribute();
        }

        return $total;
    }

    /**
     * @param Invoice[] $invoices
     * @return float
     */
    private function getInvoiceTotalSumTax($invoices): float
    {
        $total = 0;
        /** @var Invoice[] $invoice */
        foreach ($invoices as $invoice) {
            $total += $invoice->getSumTaxAttribute();

        }

        return $total;
    }

    /**
     * @param Invoice[] $invoices
     * @return float
     */
    private function getInvoiceTotalSum($invoices): float
    {
        $total = 0;
        /** @var Invoice[] $invoice */
        foreach ($invoices as $invoice) {
            $total += $invoice->getSumTotalAttribute();

        }

        return $total;

    }
}