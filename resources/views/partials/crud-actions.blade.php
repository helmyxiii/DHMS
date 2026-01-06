<div class="btn-group" role="group">
    @if(!empty($showView ?? true))
        <a href="{{ route($route . '.show', ['medical_record' => $model->id]) }}" 
           class="btn btn-sm btn-secondary" 
           title="View">
            <i class="bi bi-eye"></i> View
        </a>
    @endif
    @if(!empty($showEdit ?? true))
        <a href="{{ route($route . '.edit', ['medical_record' => $model->id]) }}" 
           class="btn btn-sm btn-primary" 
           title="Edit">
            <i class="bi bi-pencil"></i> Edit
        </a>
    @endif

    @if(!empty($showDelete ?? true))
        <form action="{{ route($route . '.destroy', ['medical_record' => $model->id]) }}" 
              method="POST" 
              class="d-inline"
              onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="btn btn-sm btn-danger" 
                    title="Delete">
                <i class="bi bi-trash"></i> Delete Record
            </button>
        </form>
    @endif

    {{-- Next/Previous navigation --}}
    @if(isset($nextId) && $nextId)
        <a href="{{ route($route . '.show', ['medical_record' => $nextId]) }}" class="btn btn-sm btn-outline-info" title="Next">
            <i class="bi bi-arrow-right"></i> Next
        </a>
    @endif
    @if(isset($prevId) && $prevId)
        <a href="{{ route($route . '.show', ['medical_record' => $prevId]) }}" class="btn btn-sm btn-outline-info" title="Previous">
            <i class="bi bi-arrow-left"></i> Previous
        </a>
    @endif
</div>
