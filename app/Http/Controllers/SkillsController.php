<?php

namespace App\Http\Controllers;

use App\Models\{Skill, SkillGroup};
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    private $validation_rules = [
        'skill.skill_group_id' => 'required|exists:skill_groups,id',
        'skill.name' => 'required|min:2',
    ];

    private $valdation_errors = [
        'skill.skill_group_id.required' => 'Wählen Sie die Skill Group aus.',
        'skill.skill_group_id.exists' => 'Wählen Sie die Skill Group aus.',
        'skill.name.required' => 'Der Name des Skills wird benötigt.',
        'skill.name.min' => 'Geben Sie mindestens 2 Zeichen ein.',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillGroupId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $skillGroupId)
    {
        $skillGroup = SkillGroup::findOrFail($skillGroupId);
        $sort = $this->sort($request, 'name');
        return view('skills/index', [
            'skillGroup' => $skillGroup, 
            'skills' => $skillGroup->skills()->orderBy($sort['sort'], $sort['dir'])->paginate($this->perPage)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillGroupId
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, int $skillGroupId)
    {
        $skillGroup = SkillGroup::findOrFail($skillGroupId);
        $skill = new Skill();
        $sort = $this->sort($request, 'name');
        return view('skills/create', [
            'skill' => $skill,
            'skillGroup' => $skillGroup, 
            'allSkillGroups' => SkillGroup::orderBy($sort['sort'], $sort['dir'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $oldSkillGroupId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $oldSkillGroupId)
    {
        $validated_data = $this->execute_validations($request);

        $skill = Skill::create($validated_data['skill']);
        $flash = ['flash_notice' => 'Der Skill '.$skill->name.' wurde angelegt.'];

        return redirect()->route('skill-groups.show', ['id' => $skill->skill_group_id])->with($flash);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $oldSkillGroupId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $skillGroupId, int $id)
    {
        $skillGroup = SkillGroup::findOrFail($skillGroupId);
        $skill = $skillGroup->skills()->findOrFail($id);
        $sort = $this->sort($request, 'name');
        return view('skills/edit', [
            'skill' => $skill,
            'skillGroup' => $skillGroup, 
            'allSkillGroups' => SkillGroup::orderBy($sort['sort'], $sort['dir'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $oldSkillGroupId
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $oldSkillGroupId, int $id)
    {
        $validated_data = $this->execute_validations($request, $id);

        $skill = Skill::findOrFail($id);
        $skill->fill($validated_data['skill']);
        $skill->save();
        $flash = ['flash_notice' => 'Der Skill '.$skill->name.' wurde gespeichert.'];

        return redirect()->route('skill-groups.show', ['id' => $skill->skill_group_id])->with($flash);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $skillGroupId
     * @param int $id
     */
    public function destroy(Request $request, int $skillGroupId, int $id)
    {
        //DB::beginTransaction();
        $skill = Skill::findOrFail($id);

        $skill->delete();
        //DB::commit();
        $flash = ['flash_notice' => 'Der Skill '.$skill->name.' wurde gelöscht.'];

        return redirect()->route('skill-groups.show', ['id' => $skill->skill_group_id])->with($flash);
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

        if (in_array($request->method(), ['PUT', 'PATCH']) && !empty($id) && is_numeric($id)) {
            $validation_rules['skill.name'] .= "|unique:skills,name,$id";
        } else {
            $validation_rules['skill.name'] .= '|unique:skills,name';
        }

        $validation_errors['skill.name.unique'] = 'Der Name des Skills ist bereits vergeben.';

        return $request->validate($validation_rules, $validation_errors);
    }
}
