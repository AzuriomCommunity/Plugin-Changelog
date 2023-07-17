<li class="sortable-item sortable-dropdown" data-category-id="{{ $category->id }}">
   <div class="card">
      <div class="card-body d-flex justify-content-between p-2 align-items-center">
         <span>
            <i class="bi bi-arrows-move sortable-handle"></i> {{ $category->name }}
         </span>
         <span>
            <a href="{{ route('changelog.admin.categories.edit', $category) }}" class="mx-1"
               title="{{ trans('messages.actions.edit') }}" data-toggle="tooltip"><i class="bi bi-pencil-square"></i></a>
            <a href="{{ route('changelog.admin.categories.destroy', $category) }}" class="mx-1"
               title="{{ trans('messages.actions.delete') }}" data-toggle="tooltip" data-confirm="delete"><i class="bi bi-trash"></i></a>
         </span>
      </div>
   </div>
</li>