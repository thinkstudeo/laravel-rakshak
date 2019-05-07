<?php

namespace Thinkstudeo\Rakshak\Http\Controllers;

use Illuminate\Http\Request;
use Thinkstudeo\Rakshak\Role;
use Thinkstudeo\Rakshak\Ability;

class RolesController extends Controller
{
    /**
     * Validation rules 
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function rules($role)
    {
        return [
            'name'        => ['required', 'unique:roles,name,' . $role->id ?: null, 'string', 'min:3', 'max:255'],
            'label'       => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'active'      => ['nullable', 'boolean'],
            'abilities'   => ['nullable', 'array'],
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('rakshak::roles.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $abilities = Ability::whereActive(1)->get();
        $role = new Role();

        return view('rakshak::roles.create', compact('abilities', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $validated = $request->validate($this->rules($request));

        $role = Role::create(array_except($validated, ['abilities']));

        if ($request->filled('abilities')) {
            foreach ($request->abilities as $ability) {
                $role->addAbility($ability['name']);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => "New role {$role->name} has been created.", 'record' => $role], 201);
        }

        return redirect(route('rakshak.roles.index'))
            ->with('status', 'success')
            ->with('message', "Role {$role->name} has been created.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Thinkstudeo\Rakshak\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $abilities = Ability::whereActive(true)->get();

        return view('rakshak::roles.edit', compact('role', 'abilities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Thinkstudeo\Rakshak\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $validated = $request->validate($this->rules($role));

        $role = tap($role)->update(array_except($validated, ['abilities']));

        if ($request->filled('abilities')) {
            $abilityIds = [];
            foreach ($request->abilities as $ability) {
                $abilityIds[] = $ability['id'];
            }
            $role->abilities()->sync($abilityIds);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => "The role {$role->name} has been updated.", 'record' => $role], 200);
        }

        return redirect()
            ->back()
            ->with("status", "success")
            ->with("message", "Role {$role->name} updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Thinkstudeo\Rakshak\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => "The role {$role->name} has been deleted."], 200);
        }

        return redirect(route('rakshak.roles.index'))
            ->with('status', 'success')
            ->with('message', "Role {$role->name} has been deleted.");
    }
}