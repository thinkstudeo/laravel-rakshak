@extends('layouts.app')

@section('content')
    <div class="w-3/5 mx-auto">
        <h1 class="text-2xl text-grey-dark font-medium my-8">Users</h1>
        <table class="w-full border-collapse table-auto bg-grey-lightest">
            <thead class="w-full text-grey-darker font-medium">
                <tr class="bg-grey-lighter">
                    <td class="flex-1 p-4">Id</td>
                    <td class="flex-1 p-4">Name</td>
                    <td class="flex-1 p-4">Email</td>
                    <td class="flex-1 p-4">Username</td>
                    <td class="flex-1 p-4">Mobile</td>
                    <td class="flex-1 p-4">Actions</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class="flex-1 p-4">{{ $user->id }}</td>
                        <td class="flex-1 p-4">{{ $user->name }}</td>
                        <td class="flex-1 p-4">{{ $user->email }}</td>
                        <td class="flex-1 p-4">{{ $user->username }}</td>
                        <td class="flex-1 p-4">{{ $user->mobile }}</td>
                        <td class="flex p-4">
                            <button class="text-sm text-grey mr-4" href="{{ route('rakshak.users.edit', ['id' => $user->id]) }}" title="edit">
                                <svg class="w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M12.3 3.7l4 4L4 20H0v-4L12.3 3.7zm1.4-1.4L16 0l4 4-2.3 2.3-4-4z"/>
                                </svg>
                            </button>
                            <form action="{{  route('rakshak.users.destroy', ['id' => $user->id]) }}" method="POST">
                                @csrf
                                <input type="text" class="hidden" name="_method" value="DELETE">
                                <button class="text-sm text-grey" type="submit" title="delete">
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