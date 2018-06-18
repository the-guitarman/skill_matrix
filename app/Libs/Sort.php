<?php

namespace App\Libs;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Route};

/**
 * This class provides sorting logic for controllers and views.
 */
abstract class Sort {
    const DEFAULT_SORT_DIR = 'asc';
    const DEFAULT_SORT_DIRS = ['asc', 'desc'];

    public static function controllerSort(Request $request, string $defaultField) {
        $currentRouteName = $request->route()->getName();
        $sort = $request->session()->get('sort', []);

        $field = $request->get('sort', $defaultField);
        $dir = $request->get('dir', self::DEFAULT_SORT_DIR);

        $dir = self::checkSortDirection($dir);
        $sort[$currentRouteName] = [$field => $dir];

        $request->session()->put("sort", $sort);
        return ['sort' => $field, 'dir' => $dir];
    }

    protected static function checkSortDirection(string $dir)
    {
        $dir = strtolower($dir);
        if (!in_array($dir, self::DEFAULT_SORT_DIRS)) {
            $dir = self::DEFAULT_SORT_DIR;
        }
        return $dir;
    }

    public static function viewSort(string $fieldName)
    {
        $currentRouteName = Route::currentRouteName();
        $sort = session('sort', []);
        $dir = self::DEFAULT_SORT_DIR;
        if (!empty($sort[$currentRouteName][$fieldName])) {
            $dir = $sort[$currentRouteName][$fieldName];
        }
        $dir = array_values(array_diff(self::DEFAULT_SORT_DIRS, [$dir]));
        return ['sort' => $fieldName, 'dir' => $dir[0]];
    }
}