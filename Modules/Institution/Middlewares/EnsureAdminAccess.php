<?php

namespace Modules\Institution\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Utilities\Response as UtilitiesResponse;
use Modules\Institution\Models\Branch;
use Modules\Institution\Models\Institution;

class EnsureAdminAccess
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
            'branch' => $this->checkBranch($request, $user->id),
            'institution' => $this->checkInstitution($request, $user->id),
            'institution_from_branch' => $this->checkInstitutionFromBranch($request, $user->id),
            default => false,
        };

        if ($allowed) {
            return $next($request);
        }

        return (new UtilitiesResponse())->error(
            message: 'Unauthorized access.'
        );
    }

    // ========================= 'ensure_institution_admin:branch'

    private function checkBranch(Request $request, int $userId): bool
    {
        $branch = Branch::findOrFail($request->route('modelId'));

        return
            $branch->institution?->owner_id == $userId

            || $branch->members()
                ->where('user_id', $userId)
                ->wherePivot('is_admin', true)
                ->exists()

            || $branch->institution?->members()
                ->where('user_id', $userId)
                ->wherePivot('is_admin', true)
                ->exists();
    }

    // ========================= 'ensure_institution_admin:institution'

    private function checkInstitution(Request $request, int $userId): bool
    {
        $modelId = $request->input('institution_id') ?? $request->route('modelId');

        $institution = Institution::findOrFail($modelId);

        return
            $institution->owner_id == $userId

            || $institution->members()
                ->where('user_id', $userId)
                ->wherePivot('is_admin', true)
                ->exists();
    }

    // ========================= 'ensure_institution_admin:institution_from_branch'

    private function checkInstitutionFromBranch(Request $request, int $userId): bool
    {
        $modelId = $request->input('branch_id') ?? $request->route('modelId');
        $branch = Branch::findOrFail($modelId);

        $institution = $branch->institution;

        return
            $institution?->owner_id == $userId

            || $institution?->members()
                ->where('user_id', $userId)
                ->wherePivot('is_admin', true)
                ->exists();
    }
}
