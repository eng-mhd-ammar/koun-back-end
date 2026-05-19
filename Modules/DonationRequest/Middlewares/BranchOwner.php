<?php

namespace Modules\DonationRequest\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Utilities\Response as UtilitiesResponse;
use Modules\DonationRequest\Models\DonationItem;
use Modules\DonationRequest\Models\DonationRequest;
use Modules\DonationRequest\Models\DonationRequestItem;
use Modules\Institution\Models\Branch;

class BranchOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $scope)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return (new UtilitiesResponse())->error(
                message: 'Unauthenticated.'
            );
        }

        if ($user->is_admin) {
            return $next($request);
        }

        $allowed = match ($scope) {
            'donation_request' => $this->checkDonationRequest($request, $user),
            'donation_request_item' => $this->checkDonationRequestItem($request, $user),
            default => false,
        };

        if ($allowed) {
            return $next($request);
        }

        return (new UtilitiesResponse())->error(
            message: "You don't have permission to access this resource.",
            code: UtilitiesResponse::HTTP_FORBIDDEN
        );
    }

    // ========================= branch_owner:donation_request
    private function checkDonationRequest(Request $request, $user): bool
    {
        $donationRequest = null;

        $receiverBranchId = $request->input('receiver_branch_id');

        // =========================
        // From receiver branch id
        // =========================
        if ($receiverBranchId) {

            $receiverBranch = Branch::find($receiverBranchId);

            if (!$receiverBranch) {
                return false;
            }

            return $receiverBranch->isEmployee($user);
        }

        // =========================
        // From donation request
        // =========================
        $donationRequestId = $request->route('modelId');

        if ($donationRequestId) {
            $donationRequest = DonationRequest::find($donationRequestId);
        }

        if (!$donationRequest) {
            return false;
        }

        $branch = $donationRequest->receiverBranch;

        // =========================
        // Personal request
        // =========================
        if (!$branch) {
            return $donationRequest->receiver_user_id === $user->id;
        }

        return $branch->isEmployee($user)
            || $donationRequest->receiver_user_id === $user->id;
    }

    // ========================= branch_owner:donation_request_item
    private function checkDonationRequestItem(Request $request, $user): bool
    {
        $donationRequest = null;

        $donationRequestId = $request->input('donation_request_id');

        // =========================
        // Direct donation request
        // =========================
        if ($donationRequestId) {
            $donationRequest = DonationRequest::find($donationRequestId);
        }

        // =========================
        // From item
        // =========================
        if (!$donationRequest) {

            $itemId = $request->route('modelId');

            if ($itemId) {

                $item = DonationRequestItem::find($itemId);

                if (!$item) {
                    return false;
                }

                $donationRequest = $item->donationRequest;
            }
        }

        // =========================
        // Validation
        // =========================
        if (!$donationRequest) {
            return false;
        }

        $branch = $donationRequest->receiverBranch;

        if (!$branch) {
            return $donationRequest->receiver_user_id === $user->id;
        }

        return $branch->isEmployee($user)
            || $donationRequest->receiver_user_id === $user->id;
    }
}
