<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $perPage = 20;
    protected $orderByDirection = 'asc';

    protected function sort(Request $request, string $defaultField) {
        $currentRouteName = $request->route()->getName();
        $sort = $request->session()->get('sort', []);

        $field = $request->get('sort', null);
        if (!empty($field)) {
            $sort[$currentRouteName]['sort'] = $field;
        }
        $dir = $request->get('dir', null);
        if (!empty($dir)) {
            $sort[$currentRouteName]['dir'] = $dir;
        }

        if (empty($sort[$currentRouteName])) {
            $sort[$currentRouteName] = [
                'sort' => $defaultField,
                'dir' => $this->orderByDirection,
            ];
        } 

        $request->session()->put("sort", $sort);
        return $sort[$currentRouteName];
    }
}
