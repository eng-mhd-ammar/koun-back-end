<?php

namespace Modules\Core\Services;

use Modules\Core\DTO\OtpDTO;
use Modules\Core\DTO\CodeDTO;
use Modules\Core\Enums\GuardType;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Enums\VerificationCodeType;
use Modules\Auth\Models\VerificationCode;
use Modules\Core\DTO\BaseDTO;
use Modules\Core\Exceptions\AuthException;
use Modules\Auth\Resources\V1\UserResource;

class BaseAuthService
{
    protected string $model = Model::class;
    protected string $guard;
    protected array $columns = ['username', 'phone'];
    protected bool $checkActivity = true;
    protected bool $markAsActivated = false;

    public function login(BaseDTO $DTO): array
    {
        $model = $this->model::whereAny($this->columns, $DTO->loginField)->firstOr(fn () => $this->throwInvalidCredentials());

        if ($this->checkActivity) {
            $model->is_active === true ?: $this->throwActivationException();
        }

        if (!Hash::check($DTO->password, $model->password)) {
            $this->throwInvalidCredentials();
        }

        $model->phone != $DTO->loginField && $model->email != $DTO->loginField ?: $this->throwUnverifiedAccount();

        $data['tokens'] = JWTToken::tokens(
            $model->id,
            $this->guard,
            $model->is_admin
        );

        $data['profile'] = UserResource::make($model);

        return $data;
    }

    public function register(BaseDTO $DTO)
    {
        $model = $this->model::query()->create($DTO->toArray());

        $model->assignRole(['user']);

        $data = UserResource::make($model);

        $this->sendCode(new CodeDTO($DTO->email ?? $DTO->phone));

        return $data;
    }

    public function refresh(): array
    {
        $refreshToken = request()->input('refresh_token');

        if (!$refreshToken) {
            $this->throwInvalidRefreshToken();
        }

        try {
            $payload = JWTAuth::setToken($refreshToken)->getPayload();
        } catch (\Exception $e) {
            $this->throwInvalidRefreshToken();
        }

        $userId = $payload['sub'] ?? null;
        $guard  = $payload['guard'] ?? GuardType::USER->value;

        if (!$userId) {
            return $this->throwInvalidRefreshToken();
        }

        $data['access_token'] = JWTToken::accessTokenById($userId);
        $data['refresh_token'] = $refreshToken;

        return $data;
    }

    public function sendCode(CodeDTO $DTO): void
    {
        $model = $this->model::query()->whereAny(['phone', 'email'], $DTO->loginField)->firstOrFail();

        $code = $this->generateRandomString();

        $type = null;

        if ($this->isEmail($DTO->loginField)) {
            $type = VerificationCodeType::EMAIL_VERIFICATION->value;
            // send to email
        } else {
            $type = VerificationCodeType::PHONE_VERIFICATION->value;
            // send to phone
        }

        $data = [
            'code' => $code,
            'expired_at' => now()->addMinutes(10),
        ];

        $attributes = [
            'type'   => $type,
            'target' => $DTO->loginField,
        ];

        $model = $model->verificationCodes()->updateOrCreate($attributes, $data);
    }

    public function checkOTP(OtpDTO $DTO)
    {
        $model = VerificationCode::query()->where('target', $DTO->loginField)->whereNull('verified_at')->orderBy('created_at', 'desc')->firstOrFail();

        if ($model->code != strtoupper($DTO->code)) {
            $this->throwInvalidOTP();
        }

        if (now()->greaterThan($model->expired_at)) {
            $this->throwOtpTimeout();
        }

        $model->update(['verified_at' => now()]);

        $data['tokens'] = JWTToken::tokens($model->user->id, $this->guard, $model->is_admin);

        $data['profile'] = UserResource::make($model->user);

        return $data;
    }

    private function generateRandomString(int $length = 6): string
    {
        return '123456';
        // return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
    }

    private function isEmail(string $text): bool
    {
        return filter_var($text, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function throwInvalidCredentials(): void
    {
        AuthException::invalidCredentials();
    }

    public function throwInvalidRefreshToken(): void
    {
        AuthException::invalidRefreshToken();
    }

    public function throwActivationException(): void
    {
        AuthException::accountHasBeenDeactivated();
    }
    public function throwUnverifiedAccount(): void
    {
        AuthException::unverifiedAccount();
    }
    public function throwUnverifiedPhoneAccount(): void
    {
        AuthException::unverifiedPhoneAccount();
    }
    public function throwUnverifiedMailAccount(): void
    {
        AuthException::unverifiedMailAccount();
    }

    public function throwInvalidOTP(): void
    {
        AuthException::invalidOtpProvided();
    }

    public function throwOtpTimeout(): void
    {
        AuthException::otpTimeout();
    }
    public function throwInvalidOldPassword(): void
    {
        AuthException::invalidOldPassword();
    }
    public function throwInvalidNewPassword(): void
    {
        AuthException::invalidNewPassword();
    }
    public function throwInvalidTokenProvided(): void
    {
        AuthException::invalidTokenProvided();
    }
}
