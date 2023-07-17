@extends('admin.layouts.admin')

@section('title', trans('changelog::admin.title'))

@push('styles')
    <style>
        #categories > .sortable-dropdown {
            padding-bottom: 1rem;
        }
    </style>
@endpush

@push('footer-scripts')
    <script src="{{ asset('vendor/sortablejs/Sortable.min.js') }}"></script>
    <script>
        const sortable = Sortable.create(document.getElementById('categories'), {
            animation: 150,
            group: 'category',
            handle: '.sortable-handle'
        });

        function serialize(categories) {
            const serialized = [];

            [].slice.call(categories.children).forEach(function (category) {

                serialized.push({
                    id: category.dataset['categoryId']
                });
            });

            return serialized
        }

        const saveButton = document.getElementById('save');
        const saveButtonIcon = saveButton.querySelector('.btn-spinner');

        saveButton.addEventListener('click', function () {
            saveButton.setAttribute('disabled', '');
            saveButtonIcon.classList.remove('d-none');

            axios.post('{{ route('changelog.admin.categories.update-order') }}', {
                'categories': serialize(sortable.el)
            })
                .then(function (json) {
                    createAlert('success', json.data.message, true);
                })
                .catch(function (error) {
                    createAlert('danger', error, true)
                })
                .finally(function () {
                    saveButton.removeAttribute('disabled');
                    saveButtonIcon.classList.add('d-none');
                });
        });
    </script>
@endpush

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">{{ trans('changelog::admin.categories.title') }}</h6>
        </div>
        <div class="card-body">
            <ol class="list-unstyled sortable" id="categories">
                @each('changelog::admin.categories._category', $categories, 'category')
            </ol>

            <a href="{{ route('changelog.admin.categories.create') }}" class="btn btn-primary"><i
                        class="fas fa-plus"></i>
                {{ trans('messages.actions.add') }}
            </a>

            @if(! $categories->isEmpty())
                <button type="button" class="btn btn-success" id="save">
                    <i class="fas fa-save"></i> {{ trans('messages.actions.save') }}
                    <span class="spinner-border spinner-border-sm btn-spinner d-none" role="status"></span>
                </button>
            @endif
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">{{ trans('changelog::admin.updates.title') }}</h6>
        </div>
        <div class="card-body">
            @if(! $categories->isEmpty())
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ trans('messages.fields.name') }}</th>
                            <th scope="col">{{ trans('changelog::messages.fields.category') }}</th>
                            <th scope="col">{{ trans('messages.fields.date') }}</th>
                            <th scope="col">{{ trans('messages.fields.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($updates as $update)
                            <tr>
                                <th scope="row">{{ $update->id }}</th>
                                <td>{{ $update->name }}</td>
                                <td>
                                    <a href="{{ route('changelog.categories.show', $update->category) }}" target="_blank">
                                        {{ $update->category->name }}
                                    </a>
                                </td>
                                <td>{{ format_date_compact($update->created_at) }}</td>
                                <td>
                                    <a href="{{ route('changelog.admin.updates.edit', $update) }}" class="mx-1"
                                       title="{{ trans('messages.actions.edit') }}" data-toggle="tooltip"><i
                                                class="fas fa-edit"></i></a>
                                    <a href="{{ route('changelog.admin.updates.destroy', $update) }}" class="mx-1"
                                       title="{{ trans('messages.actions.delete') }}" data-toggle="tooltip"
                                       data-confirm="delete"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
                {{ $updates->links() }}
                <a class="btn btn-primary" href="{{ route('changelog.admin.updates.create') }}">
                    <i class="fas fa-plus"></i> {{ trans('messages.actions.add') }}
                </a>
            @else
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-circle pr-2"></i> {{ trans('changelog::admin.categories.nothing') }}
                </div>
            @endif
        </div>
    </div>
@endsection
