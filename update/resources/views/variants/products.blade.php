@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="card-title">{{ __('All_products_under') . ' '. $variant->name }} {{ __('Variant') }}</h2>
                        </div>

                        <div class="col-md-6 d-flex justify-content-end " >
                            <button  data-toggle="modal" data-target="#addNew" class="btn btn-primary">
                                {{ __('Add_New_Variant') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped verticle-middle table-responsive-md" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>

                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#update{{ $product->id }}">
                                            <i class="far fa-edit"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="update{{ $product->id }}">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h2 class="modal-title" id="exampleModalLabel">
                                                    {{ __('Edit').' '. __('Position') }}
                                                </h2>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>
                                                <form action="{{ route('product.update.order', $product->id) }}" method="POST">
                                                    @csrf  @method('put')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <input type="text" name="position" class="form-control" value="{{ old('position') ?? $product->order }}" placeholder="Position">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
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
                            </tbody>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addNew">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Add New Variant</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form action="{{ route('variant.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="mb-1">Variant Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Variant Name">
                </div>

                <div class="mb-3">
                    <label class="mb-1">Select Service</label>
                    <x-select name="service_id">
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="mb-3">
                    <label class="mb-1">Position</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position')}}" placeholder="Position">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    </div>
</div>
@endsection
