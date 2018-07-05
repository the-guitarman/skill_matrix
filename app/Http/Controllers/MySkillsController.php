<?php

namespace App\Http\Controllers;

use App\Models\{Skill, SkillGroup, User, UserSkill};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Config};

class MySkillsController extends Controller
{
    private $validation_rules = [
        'user_skill.grade' => 'required|numeric',
    ];

    private $valdation_errors = [
        'user_skill.grade.required' => 'Bewerten Sie ihr Können mit einer Schulnote (1-6).',
        'user_skill.grade.numeric' => 'Bewerten Sie ihr Können mit einer Schulnote (1-6).',
        'user_skill.grade.min' => 'Bewerten Sie ihr Können mit einer Schulnote (1-6).',
        'user_skill.grade.max' => 'Bewerten Sie ihr Können mit einer Schulnote (1-6).',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');

        $grades = array_keys(Config::get('grade.rgb_colors'));
        $grades = array_filter(
            $grades,
            function($grade) {
                return $grade > 0;
            }
        );
        $minGrade = min($grades);
        $maxGrade = max($grades);

        $this->validation_rules['user_skill.grade'] .= "|min:$minGrade|max:$maxGrade";
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $this->sort($request, 'name');
        $users = User::where('id', Auth::user()->id)->get();
        $skillGroups = SkillGroup::with('skills')->withCount(['skills'])->orderBy($sort['sort'], $sort['dir'])->paginate($this->perPage);
        $skillCount = 0;
        foreach($skillGroups as $skillGroup) {
            $skillCount += $skillGroup->skills_count;
        }
        return view('my_skills/index', ['skillGroups' => $skillGroups, 'skillCount' => $skillCount, 'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillId
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, int $skillId)
    {
        $skill = Skill::findOrFail($skillId);
        $userSkill = new UserSkill();
        $userSkill->user_id = Auth::user()->id;
        $userSkill->skill_id = $skill->id;
        $userSkill->grade = '';
        if ($request->ajax()) {
            return view('my_skills/_create', ['userSkill' => $userSkill])->render();
        }
        return view('my_skills/create', ['userSkill' => $userSkill]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $skillId)
    {
        $validatedData = $this->execute_validations($request);

        $skill = Skill::findOrFail($skillId);
        $userSkill = Auth::user()->getUserSkill($skill->id);
        if ($userSkill) {
            Auth::user()->skills()->updateExistingPivot($skill->id, $validatedData['user_skill']);
        } else {
            Auth::user()->skills()->attach($skill->id, $validatedData['user_skill']);
        }

        $flash = ['flash_notice' => 'Ihr Skill '.$skill->name.' wurde eingetragen.'];

        return redirect()->route('skills.my.index')->with($flash);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $skillId)
    {
        $userSkill = Auth::user()->getUserSkill($skillId);
        if ($request->ajax()) {
            return view('my_skills/_edit', ['userSkill' => $userSkill])->render();
        }
        return view('my_skills/edit', ['userSkill' => $userSkill]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $skillId)
    {
        $validatedData = $this->execute_validations($request);

        $skill = Skill::findOrFail($skillId);
        Auth::user()->skills()->updateExistingPivot($skill->id, $validatedData['user_skill']);
        $flash = ['flash_notice' => 'Ihr Skill '.$skill->name.' wurde geändert.'];

        return redirect()->route('skills.my.index')->with($flash);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillId
     */
    public function destroy(Request $request, int $skillId)
    {
        //DB::beginTransaction();
        $skill = Skill::findOrFail($skillId);

        Auth::user()->skills()->detach($skill->id);
        //DB::commit();
        $flash = ['flash_notice' => 'Ihr Skill '.$skill->name.' wurde gelöscht.'];

        return redirect()->route('skills.my.index')->with($flash);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param int|null $id
     * @return Array
     */
    protected function execute_validations(Request $request, int $id = null)
    {
        $validation_rules = $this->validation_rules;
        $validation_errors = $this->valdation_errors;

        return $request->validate($validation_rules, $validation_errors);
    }
}
