@extends('layouts.app')

@section('content')
    @role('hr_manager')
        <p>I'm an H R Manager</p>
    @elserole('content_manager')
        <p>I'm a Content Manager</p>
    @else
        <p>Not an H R Manager</p>
    @endrole

    @anyrole('hr_manager|super')
        <p>Either H R Manager or Super User</p>
    @endanyrole
@endsection