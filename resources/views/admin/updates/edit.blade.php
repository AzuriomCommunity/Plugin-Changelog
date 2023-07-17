@extends('admin.layouts.admin')

@section('title', trans('changelog::admin.updates.edit', ['update' => $update->id]))

@include('admin.elements.editor')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('changelog.admin.updates.update', $update) }}" method="POST">
                @method('PUT')

                @include('changelog::admin.updates._form')

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>

                <a href="{{ route('changelog.admin.updates.destroy', $update) }}" class="btn btn-danger" data-confirm="delete">
                    <i class="bi bi-trash"></i> {{ trans('messages.actions.delete') }}
                </a>
            </form>
        </div>
    </div>
@endsection
