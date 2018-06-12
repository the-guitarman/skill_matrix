<?php

namespace App\Http\Controllers;

use App\Models\SkillGroup;
use Illuminate\Http\Request;

class SkillsGroupController extends Controller
{
    private $validation_rules = [
        'skill_group.name' => 'required|min:2',
    ];

    private $valdation_errors = [
        'skill_group.name.required' => 'Der Name der Skill Group wird benötigt.',
        'skill_group.name.min' => 'Geben Sie mindestens 2 Zeichen ein.',
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skillGroups = SkillGroup::withCount(['skills'])->orderBy('name', 'ASC')->paginate($this->perPage);
        return view('skill_groups/index', ['skillGroups' => $skillGroups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $skillGroup = new SkillGroup();
        return view('skill_groups/create', [
            'skillGroup' => $skillGroup,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data = $this->execute_validations($request);

        $skillGroup = SkillGroup::create($validated_data['skill_group']);
        if (is_numeric($skillGroup->id)) {
            $flash = ['flash_notice' => 'Die Skill Group '.$skillGroup->name.' wurde angelegt.'];
        } else {
            $flash = ['flash_error' => 'Die Skill Group konnte nicht angelegt werden.'];
        }

        return redirect()->route('skill-groups.index')->with($flash);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $id)
    {
        $skillGroup = SkillGroup::findOrFail($id);
        return view('skill_groups/show', [
            'skillGroup' => $skillGroup, 
            'skills' => $skillGroup->skills()->paginate($this->perPage)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $id)
    {
        $skillGroup = SkillGroup::withTrashed()->findOrFail($id);
        return view('skill_groups/edit', ['skillGroup' => $skillGroup]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $validated_data = $this->execute_validations($request, $id);

        $skillGroup = SkillGroup::withTrashed()->findOrFail($id);
        $skillGroup->fill($validated_data['skill_group']);

        if ($skillGroup->save()) {
            $flash = ['flash_notice' => 'Die Skill Group ' . $skillGroup->name . ' wurde gespeichert.'];
        } else {
            $flash = ['flash_error' => 'Die Skill Group ' . $skillGroup->name . ' konnte nicht gespeichert werden.'];
        }

        return redirect()->route('skill-groups.index')->with($flash);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        //DB::beginTransaction();

        $skillGroup = SkillGroup::findOrFail($id);

        if ($skillGroup->skills()->count() === 0 && $skillGroup->delete() > 0) {
            //DB::commit();
            $flash = ['flash_notice' => 'Die Skill Group '.$skillGroup->name.' wurde gelöscht.'];
        } else {
            //DB::rollBack();
            $flash = ['flash_error' => 'Die Skill Group '.$skillGroup->name.' konnte nicht gelöscht werden.'];
        }

        return redirect()->route('skill-groups.index')->with($flash);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return Array
     */
    protected function execute_validations(Request $request, int $id = null)
    {
        $validation_rules = $this->validation_rules;
        $validation_errors = $this->valdation_errors;

        if (in_array($request->method(), ['PUT', 'PATCH']) && !empty($id) && is_numeric($id)) {
            $validation_rules['skill_group.name'] .= "|unique:skill_groups,name,$id";
        } else {
            $validation_rules['skill_group.name'] .= '|unique:skill_groups,name';
        }

        $validation_errors['skill_group.name.unique'] = 'Der Name der Skill Group ist bereits vergeben.';

        return $request->validate($validation_rules, $validation_errors);
    }
}
