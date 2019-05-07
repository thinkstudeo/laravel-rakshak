

        <div class="mb-8 flex flex-col">
            <label for="name" class="text-grey-dark text-md mb-3">{{ __('Name') }}</label>
            <input
                name="name"
                type="text"
                class="text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('name') border-red-darker @enderror"
                value="{{ old('name', $role->name) }}"
                placeholder="Unique name for the role eg: view_role"
                required
                autofocus>
            @error('name')
            <div class="text-sm text-red">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-8 flex flex-col">
            <label for="label" class="text-grey-dark text-md mb-3">{{ __('Label') }}</label>
            <input
                name="label"
                type="text"
                class="text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('label') border-red-darker @enderror"
                value="{{ old('label', $role->label) }}"
                placeholder="Readable label for the role eg: View role"
                required>
            @error('label')
            <div class="text-sm text-red">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-8 flex flex-col">
            <label for="description" class="text-grey-dark text-md mb-3">{{ __('Description') }}</label>
            <input
                name="description"
                type="text"
                class="text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('description') border-red-darker @enderror"
                value="{{ old('description', $role->description) }}"
                placeholder="Short description eg: Permission to view role"
                required>
            @error('description')
            <div class="text-sm text-red">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-8 flex flex-col">
            <label for="abilities" class="text-grey-dark text-md mb-3">{{ __('Abilities') }}</label>
            <div class="inline-block relative w-full">
                <select class="block appearance-none w-full text-grey-darker border-b border-grey-light rounded hover:border-grey px-4 py-2 pr-8 leading-tight focus:outline-none">
                     <option class="text-sm text-grey bg-grey-light" value=null>Select Abilities...</option>   
                    @foreach ($abilities as $ability)
                        <option value="{{ $ability->id }}" @if($role->abilities->contains($ability)) selected @endif>{{ $ability->label }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
            @error('abilities')
            <div class="text-sm text-red">{{ $message }}</div>
            @enderror
        </div>