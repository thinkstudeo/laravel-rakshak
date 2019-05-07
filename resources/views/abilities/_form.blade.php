

        <div class="mb-8 flex flex-col">
            <label for="name" class="text-grey-dark text-md mb-3">{{ __('Name') }}</label>
            <input
                name="name"
                type="text"
                class="text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('name') border-red-darker @enderror"
                value="{{ old('name', $ability->name) }}"
                placeholder="Unique name for the ability eg: view_ability"
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
                value="{{ old('label', $ability->label) }}"
                placeholder="Readable label for the ability eg: View Ability"
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
                value="{{ old('description', $ability->description) }}"
                placeholder="Short description eg: Permission to view ability"
                required>
            @error('description')
            <div class="text-sm text-red">{{ $message }}</div>
            @enderror
        </div>