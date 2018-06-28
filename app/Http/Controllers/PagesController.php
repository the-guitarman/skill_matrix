<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{SkillGroup, Skill, User};
use App\Libs\{Sort};

class PagesController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function index(Request $request) {
        $sort = $this->sort($request, 'name');
        $users = User::orderBy('name', Sort::DEFAULT_SORT_DIR)->get();
        $skillGroups = SkillGroup::with('skills')->withCount(['skills'])->orderBy($sort['sort'], $sort['dir'])->paginate($this->perPage);
        $skillCount = 0;
        foreach($skillGroups as $skillGroup) {
            $skillCount += $skillGroup->skills_count;
        }
        return view('pages/index', ['skillGroups' => $skillGroups, 'skillCount' => $skillCount, 'users' => $users]);
    }
}
