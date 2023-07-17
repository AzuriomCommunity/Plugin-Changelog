@extends('admin.layouts.admin')

@section('title', trans('changelog::admin.updates.create'))

@include('admin.elements.editor')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('changelog.admin.updates.store') }}" method="POST" enctype="multipart/form-data">
                @include('changelog::admin.updates._form')

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
