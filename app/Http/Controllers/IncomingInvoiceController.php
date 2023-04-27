<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomingInvoiceRequest;
use App\Http\Resources\IncomingInvoiceResource;
use App\Models\IncomingInvoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IncomingInvoiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Controlling/IncomingInvoices/Index');
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Controlling/IncomingInvoices/Create', $this->props());
    }

    /**
     * @param \App\Http\Requests\IncomingInvoiceRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(IncomingInvoiceRequest $request)
    {
        $this->save(new IncomingInvoice(), $request);

        return redirect()->route('incoming.index')->with('success', __('Successfully created'));

    }

    /**
     * @param Request $request
     * @param IncomingInvoice    $incoming
     * @return \Inertia\Response
     */
    public function edit(Request $request, IncomingInvoice $incoming)
    {
        return Inertia::render('Controlling/IncomingInvoices/Edit', $this->props($incoming));
    }

    public function update(IncomingInvoiceRequest $request, IncomingInvoice $incoming)
    {
        $this->save($incoming, $request);

        return redirect()->route('incoming.index')->with('success', __('Successfully updated'));

    }

    /**
     * @param Request $request
     * @param IncomingInvoice    $incoming
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, IncomingInvoice $incoming)
    {
        $incoming->delete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('incoming.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param Request $request
     * @param IncomingInvoice    $incoming
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, IncomingInvoice $incoming)
    {
        $incoming->restore();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully restored')]);
        }

        return redirect()->route('incoming.index')->with('success', __('Successfully restored'));
    }

    /**
     * @param Request $request
     * @param IncomingInvoice    $incoming
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, IncomingInvoice $incoming)
    {
        $incoming->forceDelete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('incoming.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param IncomingInvoice|null $incoming
     * @return array
     */
    private function props( ?IncomingInvoice $incoming = null): array
    {
        return [
            'incoming' => $incoming ? IncomingInvoiceResource::make($incoming) : null,
            'payment_modes' => mapForSelect(IncomingInvoice::PAYMENT_MODES),
        ];
    }

    /**
     * @param IncomingInvoice        $incoming
     * @param IncomingInvoiceRequest $request
     * @return IncomingInvoice
     */
    private function save(IncomingInvoice $incoming, IncomingInvoiceRequest $request): IncomingInvoice
    {
        $validated = $request->validated();

        $incoming->id_number = $validated['id_number'];
        $incoming->subject = $validated['subject'];
        $incoming->gross = $validated['gross'];
        $incoming->net = $validated['net'];
        $incoming->payment_mode = $validated['payment_mode'];
        $incoming->fulfillment_date = $validated['fulfillment_date'];
        $incoming->expiration_date = $validated['expiration_date'];

        // Relations
        $incoming->createdBy()->associate($validated['created_by']);

        $incoming->save();

        $this->syncTags($incoming, $validated['type'], 'type');
        $this->syncTags($incoming, $validated['issuer'], 'issuer');

        return $incoming;
    }

    public function getTableData(Request $request){

        $perPage = $request->input('per_page');
        $page = $request->input('page');

        $invoices = IncomingInvoice::query()
            ->with('tags')
            ->withTrashed()
            ->search($request)
            ->orderBy('id', 'DESC');

        $invoices = $invoices
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => IncomingInvoiceResource::collection($invoices),
            'total' => ceil($invoices->total() / $request->input('per_page'))
        ]);
    }
}
