<div class="list-group mb-3">
    <a href="{{ route('changelog.index') }}"
       class="list-group-item d-flex justify-content-between align-items-center @if($category === null) active @endif">
        {{ trans('changelog::messages.all')}}
        <span class="badge bg-primary">
            {{ $totalUpdates }}
        </span>
    </a>
    @foreach($categories as $cat)
        <a href="{{ route('changelog.categories.show', $cat) }}"
           class="list-group-item d-flex justify-content-between align-items-center @if($cat->is($category)) active @endif">
            {{ $cat->name }}
            <span class="badge bg-primary">
                {{ $cat->updates->count() }}
            </span>
        </a>
    @endforeach
</div>
