@extends('admin.layouts.admin')

@section('title', trans('changelog::admin.categories.create'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('changelog.admin.categories.store') }}" method="POST">

                @include('changelog::admin.categories._form')

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
