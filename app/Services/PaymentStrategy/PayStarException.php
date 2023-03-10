<?php

namespace App\Services\PaymentStrategy;

class PayStarException
{
    public static array $errors = [
        -1 => 'درخواست نامعتبر (خطا در پارامترهای ورودی)',
        -2 => 'درگاه فعال نیست',
        -3 => 'توکن تکراری است',
        -4 => 'مبلغ بیشتر از سقف مجاز درگاه است',
        -5 => 'شناسه ref_num معتبر نیست',
        -6 => 'تراکنش قبلا وریفای شده است',
        -7 => 'پارامترهای ارسال شده نامعتبر است',
        -8 => 'تراکنش را نمیتوان وریفای کرد',
        -9 => 'تراکنش وریفای نشد',
        -98 => 'تراکنش ناموفق',
        -99 => 'خطای سامانه',
    ];

    public static function error($error_code): string
    {
        return self::$errors[$error_code] ?? self::$errors['-98'];
    }
}
