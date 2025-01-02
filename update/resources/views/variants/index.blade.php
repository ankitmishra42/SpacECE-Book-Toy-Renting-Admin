@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h2 class="card-title m-0">{{ __('All_Variants') }}</h2>
                        @role('store')
                            <button data-toggle="modal" data-target="#addNew" class="btn btn-primary">
                                {{ __('Add_New_Variant') }}
                            </button>
                        @endrole
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered @role('store') table-striped @endrole" id="myTable">
                                <thead>
                                    <tr>
                                        @role('root|admin')
                                            <th>{{ __('Shop_Wise_Variants') }}</th>
                                        @else
                                            <th>{{ __('SL') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Service') . ' '. __('Name') }}</th>
                                            <th class="text-center">{{ __('Position') }}</th>
                                        @endrole
                                        @role('store')
                                            <th>{{ __('Action') }}</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @role('root|admin')
                                        @foreach ($stores as $store)
                                            <tr>
                                                <td class="p-2">
                                                    <div data-toggle="collapse" data-target="#storeVariants{{ $store->id }}" class="variantGroup">
                                                       <div class="d-flex gap-4 align-items-center" style="gap: 10px">
                                                           <span>
                                                               <img src="{{ $store->logoPath }}" alt="" width="46" height="46">
                                                           </span>
                                                        <span>{{ $store->name }}</span>
                                                       </div>
                                                       <div>
                                                        <span class="badge badge-primary">{{ count($store->variants) }}</span>
                                                       </div>
                                                    </div>
                                                    <div class="collapse mt-2" id="storeVariants{{ $store->id }}">
                                                        <div class="card card-body p-2">
                                                            <table class="table table-bordered">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>{{ __('Name') }}</th>
                                                                        <th>{{ __('Service') . ' '. __('Name') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($store->variants as $variant)
                                                                    <tr>
                                                                        <td>{{ $variant->name }}</td>
                                                                        <td>{{ $variant->service?->name }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                        @endforeach
                                    @else
                                        @foreach ($variants as $key => $variant)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $variant->name }}</td>
                                                <td>{{ $variant->service?->name }}</td>
                                                <td class="text-center">{{ $variant->position ?? 0 }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#update{{ $variant->id }}">
                                                            <i class="far fa-edit"></i>
                                                        </button>

                                                        @can('variant.products')
                                                        <a href="{{ route('variant.products', $variant->id) }}"
                                                            class="btn btn-info">{{ __('Products') }}</a>
                                                        @endcan
                                                    </div>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="update{{ $variant->id }}">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h2 class="modal-title" id="exampleModalLabel">
                                                                        {{ __('Edit_Variant') }}
                                                                    </h2>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ route('variant.update', $variant->id) }}"
                                                                    method="POST">
                                                                    @csrf @method('put')
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label>{{ __('Name') }}</label>
                                                                            <input type="text" name="name"
                                                                                class="form-control"
                                                                                value="{{ old('name') ?? $variant->name }}">
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="mb-1">
                                                                                {{ __('Service') }}
                                                                            </label>
                                                                            <x-select name="service_id">
                                                                                @foreach ($services as $service)
                                                                                    <option value="{{ $service->id }}"
                                                                                        {{ $variant->service_id == $service->id ? 'selected' : '' }}>
                                                                                        {{ $service->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </x-select>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label>{{ __('Position') }}</label>
                                                                            <input type="text" name="position"
                                                                                class="form-control"
                                                                                value="{{ old('position') ?? $variant->position }}"
                                                                                placeholder="Position">
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-dismiss="modal">{{ __('Close') }}</button>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            {{ __('Save_changes') }}
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endrole
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @role('store')
        @can('variant.store')
            <!-- Modal -->
            <div class="modal fade" id="addNew">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">{{ __('Add_New_Variant') }}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('variant.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    {{ __('Name') }}
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        placeholder="Variant Name">
                                </div>
                                <div class="mb-3">
                                    <label class="mb-1">{{ __('Service') }}</label>
                                    <x-select name="service_id">
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>

                                <div class="mb-3">
                                    <label>{{ __('Position') }}</label>
                                    <input type="text" name="position" class="form-control" value="{{ old('position') }}"
                                        placeholder="Position">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endrole
@endsection
