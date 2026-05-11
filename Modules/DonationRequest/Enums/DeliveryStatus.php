<?php

namespace Modules\DonationRequest\Enums;

enum DeliveryStatus: int
{
    case PENDING = 0;
    case PICKED_UP = 1;
    case IN_TRANSIT = 2;
    case DELIVERED = 3;

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
