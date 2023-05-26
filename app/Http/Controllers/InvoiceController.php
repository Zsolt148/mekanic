<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PartnerResource;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Controlling/Invoices/Index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Controlling/Invoices/Create', $this->props());
    }

    /**
     * @param \App\Http\Requests\InvoiceRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(InvoiceRequest $request)
    {
		$this->save(new Invoice(), $request);

        return redirect()->route('invoices.index')->with('success', __('Successfully created'));

    }

    /**
     * @param Request $request
     * @param Invoice    $invoice
     * @return \Inertia\Response
     */
    public function edit(Request $request, Invoice $invoice)
    {
        return Inertia::render('Controlling/Invoices/Edit', $this->props($invoice));
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $this->save($invoice, $request);

        return redirect()->route('invoices.index')->with('success', __('Successfully updated'));

    }

    /**
     * @param Request $request
     * @param Invoice    $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Invoice $invoice)
    {
        foreach($invoice->invoiceItems as $invoiceItem){
            $invoiceItem->delete();
        }

        $invoice->delete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('invoices.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param Request $request
     * @param Invoice    $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Invoice $invoice)
    {
        $invoice->restore();

        foreach($invoice->invoiceItems as $invoiceItem){
            $invoiceItem->restore();
        }

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully restored')]);
        }

        return redirect()->route('invoices.index')->with('success', __('Successfully restored'));
    }

    /**
     * @param Request $request
     * @param Invoice    $invoice
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, Invoice $invoice)
    {
        foreach($invoice->invoiceItems()->withTrashed()->get() as $item){
            $item->forceDelete();
        }

        $invoice->forceDelete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('invoices.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param Invoice|null $invoice
     * @return array
     */
    private function props(?Invoice $invoice = null): array
    {
        $partners = Partner::query()->orderBy('name', 'DESC')->get();

        return [
            'invoice' => $invoice ? InvoiceResource::make($invoice) : null,
            'payment_modes' => mapForSelect(Invoice::PAYMENT_MODES),
            'partners' => PartnerResource::collection($partners)
        ];
    }

    /**
     * @param Invoice        $invoice
     * @param InvoiceRequest $request
     * @return Invoice
     */
    private function save(Invoice $invoice, InvoiceRequest $request): Invoice
    {
        $validated = $request->validated();

        if($validated['partner_id'] != null) {
            $partner = Partner::find($validated['partner_id']);
            $partner->name = $validated['name'];
            $partner->zip = $validated['zip'];
            $partner->city = $validated['city'];
            $partner->address = $validated['address'];
            $partner->tax_number = $validated['tax_number'];
            $partner->communal_tax_number = $validated['communal_tax_number'];
            $partner->save();
        }else {
            $partner = Partner::create([
                'name' => $validated['name'],
                'zip' => $validated['zip'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'tax_number' => $validated['tax_number'],
                'communal_tax_number' => $validated['communal_tax_number'],
                'created_by' => Auth::user()->id
            ]);
        }

        $invoice->partner_id = $partner->id;
        $invoice->created_by = $validated['created_by'];
        $invoice->order_number = $validated['order_number'];
        $invoice->net = $validated['net'];
        $invoice->tax = $validated['tax'];
        $invoice->gross = $validated['gross'];
        $invoice->payment_mode = $validated['payment_mode'];
        $invoice->issue_date = $validated['fulfillment_date'];
        $invoice->fulfillment_date = $validated['fulfillment_date'];
        $invoice->expiration_date = $validated['expiration_date'];
        $invoice->comment = $validated['comment'];

        $invoice->save();

        foreach($invoice->invoiceItems as $item){
            $item->forceDelete();
        }

        foreach($validated['invoice_items'] as $item) {
            $invoiceItem = InvoiceItem::create([
                    'created_by' => $validated['created_by'],
                    'invoice_id' => $invoice->id,
                    'name' => $item["name"],
                    'quantity' => $item["quantity"],
                    'unit_price' => $item["unit_price"],
                    'tax_percent' => $item["tax_percent"],
                    'net' => $item["net"],
                    'tax' => $item["tax"],
                    'gross' => $item["gross"],
            ]);
			$invoiceItem->createdBy()->associate($validated['created_by']);

            $this->syncTags($invoiceItem, $item['quantity_unit'], 'quantity_unit');
        }

        return $invoice;
    }

    public function getTableData(Request $request) {

        $perPage = $request->input('per_page');
        $page = $request->input('page');

        $invoices = Invoice::query()
            ->with(['partner', 'invoiceItems'])
            ->withTrashed()
            ->search($request)
            ->orderBy('id', 'DESC');

        $invoices = $invoices
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => InvoiceResource::collection($invoices),
            'total' => ceil($invoices->total() / $request->input('per_page'))
        ]);
    }
}
