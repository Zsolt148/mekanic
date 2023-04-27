<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Http\Resources\CheckoutResource;
use App\Models\Checkout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Controlling/Checkout/Index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        return Inertia::render('Controlling/Checkout/Create', $this->props());
    }

    /**
     * @param \App\Http\Requests\CheckoutRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(CheckoutRequest $request)
    {
        return redirect()->route('checkout.index')->with('success', __('Successfully created'));

    }

    /**
     * @param Request $request
     * @param Checkout    $checkout
     * @return \Inertia\Response
     */
    public function edit(Request $request, Checkout $checkout)
    {
        return Inertia::render('Controlling/Checkout/Edit', $this->props($checkout));
    }

    public function update(CheckoutRequest $request, Checkout $checkout)
    {
        $this->save($checkout, $request);

        return redirect()->route('checkout.index')->with('success', __('Successfully updated'));

    }

    /**
     * @param Request $request
     * @param Checkout    $checkout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Checkout $checkout)
    {
        $checkout->delete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('checkout.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param Request $request
     * @param Checkout    $checkout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request, Checkout $checkout)
    {
        $checkout->restore();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully restored')]);
        }

        return redirect()->route('checkout.index')->with('success', __('Successfully restored'));
    }

    /**
     * @param Request $request
     * @param Checkout    $checkout
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Request $request, Checkout $checkout)
    {
        $checkout->forceDelete();

        if($request->wantsJson()) {
            return response()
                ->json(['success', __('Successfully deleted')]);
        }

        return redirect()->route('checkout.index')->with('success', __('Successfully deleted'));
    }

    /**
     * @param Checkout|null $checkout
     * @return array
     */
    private function props( ?Checkout $checkout = null): array
    {
        return [
            'checkout' => $checkout ? CheckoutResource::make($checkout) : null,
            'types' => mapForSelect(Checkout::TYPE),
        ];
    }

    /**
     * @param Checkout        $checkout
     * @param CheckoutRequest $request
     * @return Checkout
     */
    private function save(Checkout $checkout, CheckoutRequest $request): Checkout
    {
        $validated = $request->validated();

        $checkout->type = $validated['type'];
        $checkout->date = $validated['date'];
        $checkout->receipt_number = $validated['receipt_number'];
        $checkout->claimant = $validated['claimant'];
        $checkout->amount = $validated['amount'];

        // Relations
        $checkout->createdBy()->associate($validated['created_by']);

        $checkout->save();

        $this->syncTags($checkout, $validated['title'], 'title');

        return $checkout;
    }

    protected function hasPermissionTo(string $permission)
    {
        $user = $this->user();

        abort_if(!$user->hasPermissionTo($permission), 403);
    }

    public function getTableData(Request $request){

        $perPage = $request->input('per_page');
        $page = $request->input('page');

        $checkouts = Checkout::query()
            ->with('tags')
            ->withTrashed()
            ->search($request)
            ->orderBy('id', 'DESC');
        $checkouts = $checkouts
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);

        $plusCheckouts = $checkouts->filter(function ($checkout) {
            return $checkout->type == 'inpayment' && $checkout->trashed() == false;
        });

        $minusCheckouts = $checkouts->filter(function ($checkout) {
            return $checkout->type != 'inpayment' && $checkout->trashed() == false;
        });

        $allPlus = $plusCheckouts->sum('amount');
        $allMinus = $minusCheckouts->sum('amount');
        $allCheckout = $allPlus - $allMinus;

        return response()->json([
            'data' => CheckoutResource::collection($checkouts),
            'total' => ceil($checkouts->total() / $request->input('per_page')),
            'plusCheckout' => getCurrencyFormat($allPlus),
            'minusCheckout' => getCurrencyFormat($allMinus),
            'allCheckout' => getCurrencyFormat($allCheckout)
        ]);
    }
}
