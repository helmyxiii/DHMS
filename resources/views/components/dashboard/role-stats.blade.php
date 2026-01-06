@props(['role'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                @switch($role->slug)
                    @case('admin')
                        <i class="bi bi-shield-lock text-white text-2xl"></i>
                        @break
                    @case('doctor')
                        <i class="bi bi-person-badge text-white text-2xl"></i>
                        @break
                    @case('patient')
                        <i class="bi bi-people text-white text-2xl"></i>
                        @break
                    @default
                        <i class="bi bi-person text-white text-2xl"></i>
                @endswitch
            </div>
            <div class="ml-5 w-0 flex-1">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        {{ $role->name }} Users
                    </dt>
                    <dd class="flex items-baseline">
                        <div class="text-2xl font-semibold text-gray-900">
                            {{ $role->users_count }}
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div> 