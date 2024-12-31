<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InvoiceManage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceManageController extends Controller
{
    public function index()
    {
        $invoice = auth()->user()->invoice;

        return view('invoice-manage', compact('invoice'));
    }

    public function preview($name)
    {
        return Pdf::loadView('invoice-preview.'.$name)->setPaper('a4', 'portrait')->stream();
    }

    public function update(Request $request)
    {
        $request->validate(([
            'type' => 'required|string',
        ]));

        $invoice = InvoiceManage::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'type' => $request->type ?? 'regular',
            ]
        );

        return $this->json('Printer type updated successfully',[
            'type' => $invoice->type,
        ]);
    }

    public function pdfUpdate(Request $request)
    {
        $invoice = InvoiceManage::updateOrCreate(
            [
                'user_id' => auth()->id(),
            ],
            [
                'type' => auth()->user()->invoice?->type ?? 'regular',
                'invoice_name' => $request->invoice ?? 'regular',
            ]
        );

        return $this->json('Invoice updated successfully',[
            'invoice' => $invoice->invoice_name,
        ]);
    }
}
