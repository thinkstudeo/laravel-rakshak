@extends('layouts.app')

@section('content')
<div class="w-full bg-white flex justify-center items-start h-full pt-24">
    <div class="lg:w-1/2 lg:max-w-md flex flex-col shadow-md border-t-8 border-purple rounded">

        <form method="POST" action="{{ route('guardian.users.update', ['id'=> $user->id]) }}" class="p-8 bg-grey-lighter">
            @csrf
            <input type="text" class="hidden" name="_method" value="PUT">

            <div class="mb-10">
                <h1 class="text-2xl font-bold mb-2">{{ __('Edit User') }}</h1>
            </div>

            @include('guardian::users._form')


            <div class="flex justify-between items-center">
                <a href=""></a>
                <button type="submit" class="py-2 px-6 bg-purple hover:bg-purple-dark text-purple-lightest rounded shadow transition-03">{{ __('Update') }}</button>
            </div>

        </form>
    </div>
</div>
@endsection