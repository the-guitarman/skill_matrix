<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\{Auth, Lang};
use App\Libs\Sort;

class Helper
{
    /**
     * Prüft, ob der angemeldete Benutzer ein Recht für eine Eloquent-Modellklasse hat.
     *
     * @param string $recht
     * @param string $className
     * @return boolean
     *
     * Verwendung:
     * currentUserCan('lesen', \App\Models\Arbeitgeber::class) => true|false
     */
    public static function currentUserCan($recht, $className)
    {
        return Auth::user()->can($recht, $className);
    }

    public static function localize($value, $formatKey = 'date.formats.datetime.long')
    {
        $ergebnis = $value;
        if (is_array($value) && !empty($value['date'])) {
            $ergebnis = Carbon::parse($value['date'])->format(Lang::get($formatKey));
        } elseif (is_string($value)) {
            $ergebnis = Carbon::parse($value)->format(Lang::get($formatKey));
        } elseif (class_basename($value) === 'Carbon') {
            $ergebnis = $value->format(Lang::get($formatKey));
        }
        return $ergebnis;
    }

    public static function sort(string $fieldName)
    {
        return Sort::viewSort($fieldName);
    }
}