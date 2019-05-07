@extends('layouts.app')

@section('content')
<div class="w-full bg-white flex flex-col justify-center items-center h-full pt-24">

    <div class="my-6 lg:w-1/2 lg:max-w-md">
        <a 
            href="{{ route('guardian.abilities.index') }}" 
            class="py-2 px-4 flex items-center text-sm text-grey-dark hover:text-purple">
            <svg class="w-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M19 5v10l-9-5 9-5zm-9 0v10l-9-5 9-5z"/></svg>
            Back
        </a>
    </div>

    <div class="lg:w-1/2 lg:max-w-md flex flex-col shadow-md border-t-8 border-purple rounded">
        <form action="{{ $ability->path() }}" method="POST" class="p-8 bg-grey-lighter">
            @if (session('status'))
                <div class="bg-green-lightest p-6 text-green-dark border-l-8 border-green-dark mb-4 shadow">
                    {{ session('message') }}
                </div>
            @endif
            @csrf
            @method('PATCH')

            <div class="mb-10">
                <h1 class="text-2xl font-bold mb-2">{{ __('Edit Ability') }}</h1>
            </div>

            @include('guardian::abilities._form')

            <div class="flex justify-end items-center">
                <button type="submit" class="py-2 px-6 bg-purple hover:bg-purple-dark text-purple-lightest rounded shadow transition-03">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection