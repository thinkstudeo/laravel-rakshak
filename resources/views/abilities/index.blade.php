@extends('layouts.app')

@section('content')
    <div class="w-3/5 mx-auto">
        <div class="w-full flex justify-between items-center">
            <h1 class="text-2xl text-grey-dark font-medium my-8">Abilities</h1>
            <a 
                href="{{ route('guardian.abilities.create') }}" 
                class="px-4 py-2 bg-purple text-purple-lightest hover:bg-purple-darker transition-05"
            >Add Ability</a>
        </div>

        @if (session('status'))
            <div class="bg-green-lightest p-6 text-green-dark border-l-8 border-green-dark mb-4 shadow">
                {{ session('message') }}
            </div>
        @endif
        
        <table class="w-full border-collapse table-auto bg-grey-lightest text-grey-darker text-md">
            <thead class="w-full text-grey-darkest font-medium">
                <tr class="bg-grey-light">
                    <td class="flex-1 p-4">Id</td>
                    <td class="flex-1 p-4">Name</td>
                    <td class="flex-1 p-4">Label</td>
                    <td class="flex-1 p-4">Description</td>
                    <td class="flex-1 p-4">Actions</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($abilities as $ability)
                    <tr class="{{ $loop->even ? 'bg-grey-lighter': 'bg-grey-lightest' }}">
                        <td class="flex-1 p-4">{{ $ability->id }}</td>
                        <td class="flex-1 p-4">{{ $ability->name }}</td>
                        <td class="flex-1 p-4">{{ $ability->label }}</td>
                        <td class="flex-1 p-4">{{ $ability->description }}</td>
                        <td class="flex p-4">
                            <a class="text-sm text-grey mr-4 hover:text-grey-darker" href="{{ $ability->path() . '/edit' }}" title="Edit">
                                <svg class="w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                                </svg>
                            </a>
                            <form action="{{ $ability->path() }}" method="POST" title="Delete">
                                @csrf
                                @method('DELETE')
                                <button class="text-sm text-grey hover:text-grey-darker" type="submit">
                                    <svg class="w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M6 2l2-2h4l2 2h4v2H2V2h4zM3 6h14l-1 14H4L3 6zm5 2v10h1V8H8zm3 0v10h1V8h-1z"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection