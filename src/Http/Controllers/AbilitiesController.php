<?php

namespace Thinkstudeo\Guardian\Http\Controllers;

use Illuminate\Http\Request;
use Thinkstudeo\Guardian\Ability;

class AbilitiesController extends Controller
{
    /**
     * Validation rules 
     *
     * @param Ability $ability
     * @return mixed
     */
    protected function rules($ability)
    {
        return [
            'name'        => ['required', 'string', 'unique:abilities,name,' . $ability->id ?: null, 'min:3', 'max:255'],
            'label'       => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'active'      => ['nullable', 'boolean']
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Ability::class);

        $abilities = Ability::all();

        return view('guardian::abilities.index', compact('abilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ability = new Ability();

        return view('guardian::abilities.create', compact('ability'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Ability::class);

        $validated = $request->validate($this->rules($request));

        $ability = Ability::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => "New ability {$ability->name} has been created.", 'record' => $ability], 201);
        }

        return redirect(route('guardian.abilities.index'))
            ->with('status', 'success')
            ->with('message', "Ability {$ability->name} has been created.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Ability $ability
     * @return \Illuminate\Http\Response
     */
    public function edit(Ability $ability)
    {
        $this->authorize('update', $ability);

        return view('guardian::abilities.edit', compact('ability'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Thinkstudeo\Guardian\Ability $ability
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ability $ability)
    {
        $this->authorize('update', $ability);

        $validated = $request->validate($this->rules($ability));

        $ability = tap($ability)->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => "The ability {$ability->name} has been updated.", 'record' => $ability], 200);
        }

        return redirect()
            ->back()
            ->with("status", "success")
            ->with("message", "Ability {$ability->name} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Thinkstudeo\Guardian\Ability $ability
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ability $ability)
    {
        $ability->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => "The ability {$ability->name} has been deleted."]);
        }

        return redirect(route('guardian.abilities.index'))
            ->with('status', 'success')
            ->with('message', "Ability {$ability->name} has been deleted.");
    }
}