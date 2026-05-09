<?php

namespace Modules\Donation\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\User;
use Modules\Core\Utilities\Response as UtilitiesResponse;
use Modules\Donation\Models\Donation;
use Modules\Donation\Models\DonationItem;
use Modules\Institution\Models\Branch;

class DonationOwner
{
    public function handle(Request $request, Closure $next, string $scope)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return (new UtilitiesResponse())->error(message: 'Unauthenticated.');
        }

        if ($user->is_admin) {
            return $next($request);
        }

        $allowed = match ($scope) {
            'donation_item' => $this->checkDonationItem($request, $user),
            'donation' => $this->checkDonation($request, $user),
            default => false,
        };

        if ($allowed) {
            return $next($request);
        }

        return (new UtilitiesResponse())->error(message: "You don't have permission to access this resource.", code: UtilitiesResponse::HTTP_FORBIDDEN);
    }

    // ========================= donation_owner:donation_item
    private function checkDonationItem(Request $request, $user): bool
    {
        $donationId = $request->input('donation_id');

        if (!$donationId) {
            $item = DonationItem::find($request->route('modelId'));
            $donationId = $item?->donation_id;
        }

        if (!$donationId) {
            return false;
        }

        $donation = Donation::find($donationId);

        if (!$donation || !$donation->senderBranch) {
            return false;
        }

        return $user->id === $donation->sender_user_id || $donation->senderBranch->isEmployee($user->id);
    }

    // ========================= donation_owner:donation
    private function checkDonation(Request $request, $user): bool
    {
        $branch = null;

        $branchId = $request->input('sender_branch_id');

        if ($branchId) {
            $branch = Branch::find($branchId);
        }

        if (!$branch) {
            $donationId = $request->route('modelId');

            if($donationId) {
                $donation = $donationId
                    ? Donation::find($donationId)
                    : null;

                if (!$donation) {
                    return false;
                }

                $branch = $donation->senderBranch;

            } else {
                return $donation?->sender_user_id === $user->id;
            }
        }

        if (!$branch) {
            return false;
        }

        $donation = $branch->donation;

        return $branch->isEmployee($user);
    }
}
