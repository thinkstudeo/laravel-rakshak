

<div class="mb-8 flex items-center">
    <label for="name" class="text-grey-dark text-sm w-32">{{ __('Name') }}</label>
    <input
        name="name"
        type="text"
        class="flex-1 text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('name') border-red-darker @enderror"
        value="{{ old('name', $user->name) }}"
        autocomplete="name"
        placeholder="First Name <space> Last Name Eg:John Doe"
        required
        autofocus>
    @error('email')
    <div class="text-sm text-red">{{ $message }}</div>
    @enderror
</div>

<div class="mb-8 flex items-center">
    <label for="username" class="text-grey-dark text-sm w-32">{{ __('Username') }}</label>
    <input
        name="username"
        type="text"
        class="flex-1 text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('username') border-red-darker @enderror"
        value="{{ old('username', $user->username) }}"
        autocomplete="name"
        placeholder="Without spaces or special characters Eg: johndoe"
        required>
    @error('username')
    <div class="text-sm text-red">{{ $message }}</div>
    @enderror
</div>

<div class="mb-8 flex items-center">
    <label for="email" class="text-grey-dark text-sm w-32">{{ __('E-Mail Address') }}</label>
    <input
        name="email"
        type="email"
        class="flex-1 text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('email') border-red-darker @enderror"
        value="{{ old('email', $user->email) }}"
        autocomplete="email"
        placeholder="Your email address"
        required>
    @error('email')
    <div class="text-sm text-red">{{ $message }}</div>
    @enderror
</div>

<div class="mb-8 flex items-center">
    <label for="mobile" class="text-grey-dark text-sm w-32">{{ __('Mobile Number') }}</label>
    <input
        name="mobile"
        type="tel"
        class="flex-1 text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('mobile') border-red-darker @enderror"
        value="{{ old('mobile', $user->mobile) }}"
        autocomplete="tel"
        placeholder="With country code Eg: +919898989898"
        required>
    @error('mobile')
    <div class="text-sm text-red">{{ $message }}</div>
    @enderror
</div>

<div class="mb-8 flex flex-col">
    <label for="roles" class="text-grey-dark text-md mb-3">{{ __('Roles') }}</label>
    <div class="inline-block relative w-full">
        <select class="block appearance-none w-full text-grey-darker border-b border-grey-light rounded hover:border-grey px-4 py-2 pr-8 leading-tight focus:outline-none">
            <option class="text-sm text-grey bg-grey-light" value=null>Select Roles...</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @if($user->roles->contains($role)) selected @endif>{{ $role->label }}</option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
    </div>
    @error('roles')
    <div class="text-sm text-red">{{ $message }}</div>
    @enderror
