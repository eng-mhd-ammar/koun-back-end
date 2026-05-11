<?php

namespace Modules\DonationRequest\Enums;

enum DonationRequestStatus: int
{
    case REJECTED = -1;
    case PENDING = 0;
    case APPROVED = 1;

    public function label(): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $this->name)));
    }

    public static function tableComment(): string
    {
        $labels = '';
        for ($i = 0; $i < count(self::cases()); $i++) {
            $case = self::cases()[$i];
            if ($i == count(self::cases()) - 1) {
                $labels = "$labels{$case->value} => {$case->label()}";
                continue;
            }
            $labels = "$labels{$case->value} => {$case->label()}, ";
        }
        return $labels;
    }
}
