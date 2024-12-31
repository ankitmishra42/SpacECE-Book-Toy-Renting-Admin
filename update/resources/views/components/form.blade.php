<div>
    <div class="basic-form">
        <form @can($route) action="{{ route($route, $updateId) }}"@endcan method="POST" enctype="multipart/form-data"> @csrf
            @if($method)  @method('put') @endif
            {{ $slot }}

            @can($route)
            <div class="form-group text-right mt-3 mb-0">
                <button type="submit" class="btn btn-primary mb-2">{{ __($type) }}</button>
            </div>
            @endcan
        </form>
    </div>
</div>
