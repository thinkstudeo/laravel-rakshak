@extends('layouts.app')

@section('content')    
<div class="w-full bg-white flex justify-center items-start h-full pt-24">
    <div class="lg:w-1/2 lg:max-w-md flex flex-col shadow-md border-t-8 border-purple rounded">
        <form action="{{ route('guardian.settings.update') }}" method="POST" class="p-8 bg-grey-lighter">
            @if (session('status'))
                <div class="bg-green-lightest p-6 text-green-dark border-l-8 border-green-dark mb-4 shadow">
                    {{ session('message') }}
                </div>
            @endif
            @csrf
            @method('PUT')

            <div class="mb-10">
                <h1 class="text-2xl font-bold mb-2">{{ __('Edit Guardian Settings') }}</h1>
            </div>

            <div class="mb-8 flex">
                <label class="text-grey-dark text-md mb-3">
                    <input class="mr-2 leading-tight" type="radio">
                    <span class="text-grey-darker">
                    Enable Two Factor Authentication
                    </span>
                </label>
            </div>

            <div class="mb-8 flex flex-col">
                <label for="channel_2fa" class="text-grey-dark text-md mb-3">{{ __('Two Factor Channel') }}</label>
                <div class="inline-block relative w-full">
                    <select class="block appearance-none w-full text-grey-darker border-b border-grey-light rounded hover:border-grey px-4 py-2 pr-8 leading-tight focus:outline-none">
                         <option class="text-sm text-grey bg-grey-light" value=null>Select Channel</option>   
                        @foreach (['email', 'sms'] as $channel)
                            <option value="{{ $channel }}" @if($setting->channel_2fa === $channel) selected @endif>{{ Str::title($channel) }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
                @error('name')
                <div class="text-sm text-red">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-8 flex flex-col">
                <label for="control_level_2fa" class="text-grey-dark text-md mb-3">{{ __('Two Factor Control') }}</label>
                <div class="inline-block relative w-full">
                    <select class="block appearance-none w-full text-grey-darker border-b border-grey-light rounded hover:border-grey px-4 py-2 pr-8 leading-tight focus:outline-none">
                         <option class="text-sm text-grey bg-grey-light" value=null>Control lies with...</option>   
                        @foreach (['admin', 'user'] as $control)
                            <option value="{{ $control }}" @if($setting->control_level_2fa === $control) selected @endif>{{ Str::title($control) }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
                @error('name')
                <div class="text-sm text-red">{{ $message }}</div>
                @enderror
            </div>
            

            <div class="flex justify-end items-center">
                <button type="submit" class="py-2 px-6 bg-purple hover:bg-purple-dark text-purple-lightest rounded shadow transition-03">{{ __('Update') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection