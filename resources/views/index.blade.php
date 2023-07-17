@extends('layouts.app')

@section('title', trans('changelog::messages.title'))

@section('content')
    <div class="alert alert-warning" role="alert">
        {{ trans('changelog::messages.empty') }}
    </div>
@endsection
