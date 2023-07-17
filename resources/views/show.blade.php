@extends('layouts.app')

@section('title', $category->name ?? trans('changelog::messages.title'))

@section('content')
    <h1>{{ $category->name ?? trans('changelog::messages.title') }}</h1>

    <div class="row">
        <div class="col-md-3">
            @include('changelog::sidebar')
        </div>
        <div class="col-md-9">
            @forelse($updates as $update)
                <div class="card mb-3">
                    <div class="card-body">
                        <h2 class="card-title mb-2">{{ $update->name }}</h2>

                        <p>
                            <span class="badge bg-primary me-1 small">
                                <i class="bi bi-folder"></i> {{ $update->category->name }}
                            </span>
                            <span class="badge bg-primary me-1 small">
                                <i class="bi bi-calendar"></i> {{ format_date($update->created_at) }}
                            </span>
                        </p>

                        {!! $update->description !!}
                    </div>
                </div>
            @empty
                <div class="alert alert-warning" role="alert">
                    {{ trans('changelog::messages.categories.empty') }}
                </div>
            @endforelse

            {{ $updates->links() }}
        </div>
    </div>
@endsection
