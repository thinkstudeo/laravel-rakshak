@extends('layouts.app')

@section('content')
<div class="w-full bg-white flex justify-center items-start h-full pt-24">
    <div class="lg:w-1/2 lg:max-w-md flex flex-col shadow-md border-t-8 border-purple rounded">

        <form method="POST" action="{{ route('rakshak.users.store') }}" class="p-8 bg-grey-lighter">
            @csrf
            <div class="mb-10">
                <h1 class="text-2xl font-bold mb-2">{{ __('Create a new User') }}</h1>
            </div>

            @include('rakshak::users._form')

            {{-- <div class="mb-8 flex items-center">
                <label for="password" class="text-grey-dark text-sm w-32">{{ __('Password') }}</label>
                <input
                    name="password"
                    type="password"
                    class="flex-1 text-grey-darker px-4 py-2 border-b border-grey-light rounded bg-grey-lightest @error('password') border-red-darker @enderror"
                    value="{{ old('password') }}"
                    autocomplete="new-password"
                    placeholder="Minimum 8 characters"
                    required>
                @error('password')
                <div class="text-sm text-red">{{ $message }}</div>
                @enderror
            </div> --}}


            <div class="flex justify-between items-center">
                <a href=""></a>
                <button type="submit" class="py-2 px-6 bg-purple hover:bg-purple-dark text-purple-lightest rounded shadow transition-03">{{ __('Save') }}</button>
            </div>

        </form>
    </div>
</div>
@endsection