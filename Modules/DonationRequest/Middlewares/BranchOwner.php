<?php

namespace Modules\DonationRequest\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Utilities\Response as UtilitiesResponse;
use Modules\DonationRequest\Models\DonationItem;
use Modules\DonationRequest\Models\DonationRequest;
use Modules\Institution\Models\Branch;

class BranchOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if($user->is_admin) {
            return $next($request);
        }

        $branchId = $request->input('receiver_branch_id');
        $branch = null;

        if ($branchId)
            $branch = Branch::findOrFail($branchId);
        else
            $branch = DonationRequest::query()->findOrFail($request->route('modelId'))->receiverBranch;

        if($branch->isEmployee($user)) {
            return $next($request);
        }

        return (new UtilitiesResponse)->error('You are not authorized to perform this action.', UtilitiesResponse::HTTP_FORBIDDEN);
    }
}
