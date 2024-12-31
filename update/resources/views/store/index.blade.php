@extends('layouts.app')

@section('content')
    <div class="mt-3 container-fluid">
        <div class="row mt-md-5">
            <div class="col-lg-6 mb-3">
                <div class="card rounded-8">
                    <div class="card-header py-2 d-flex justify-content-between bg-primary">
                        <h2 class="card-title m-0 text-white">{{ __('Details') }}</h2>
                        <a href="{{ route('store.edit') }}" class="btn btn-outline-neutral btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <td>{{ $store->name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Delivery_charge') }}</th>
                                <td>{{ currencyPosition($store->delivery_charge) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Minimum_order_amount') }}</th>
                                <td>{{ currencyPosition($store->min_order_amount) }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Create') .' '. __('Date') }}</th>
                                <td>{{ $store->created_at->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Prefix') }}</th>
                                <td>{{ $store->prifix }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Description') }}</th>
                                <td>
                                    <div style="max-height: 100px; overflow-y: auto">
                                        {{ $store->description }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Logo') }}</th>
                                <td>
                                    <img src="{{ $store->logo?->file }}" alt="" height="50">
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Banner') }}</th>
                                <td>
                                    <img src="{{ $store->banner?->file }}" alt="" width="100">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card rounded-8">
                    <div class="card-header py-2 d-flex justify-content-between bg-primary">
                        <h2 class="card-title m-0 text-white">{{ __('Set_Location') }}</h2>
                        <form action="{{ route('store.location-update') }}" method="POST"> @method('put') @csrf
                            <input type="hidden" name="lat" id="lat" value="">
                            <input type="hidden" name="lng" id="lng" value="">
                            <button class="btn btn-outline-neutral btn-sm">
                                {{ __('Save_And_Update') }}
                            </button>
                        </form>
                    </div>
                    <div class="card-body" style="height: 508px">
                        <div id="mapCanvas" style="width: 100%; height: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card rounded-8">
                    <div class="card-header py-2 d-flex justify-content-between bg-primary">
                        <h2 class="card-title m-0 text-white">{{ __('Personal_Details') }}</h2>
                        <button class="btn btn-outline-neutral btn-sm" data-toggle="modal" data-target="#editUserModal"> <i
                                class="fas fa-edit"></i></button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('First_Name') }}</th>
                                <td>{{ $store->user->first_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Last_Name') }}</th>
                                <td>{{ $store->user->last_name ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Mobile') }}</th>
                                <td>{{ $store->user->mobile }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $store->user->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Gender') }}</th>
                                <td>{{ $store->user->gender ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Profile_Photo') }}</th>
                                <td>
                                    <img src="{{ $store->user->profile_photo_path }}" alt="" width="100">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card rounded-8">
                    <div class="card-header py-2 d-flex justify-content-between bg-primary">
                        <h2 class="card-title m-0 text-white">{{ __('Address') }}</h2>
                        <button class="btn btn-outline-neutral btn-sm" data-toggle="modal" data-target="#editAddressModal">
                            <i class="fas fa-edit"></i></button>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('Address_Name') }}</th>
                                <td>{{ $store->address?->address_name ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Area') }}</th>
                                <td>{{ $store->address?->area ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Road_No') }}:</th>
                                <td>{{ $store->address?->road_no ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Block') }}:</th>
                                <td>{{ $store->address?->block ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('House_No') }}:</th>
                                <td>{{ $store->address?->house_no ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Flat_No') }}:</th>
                                <td>{{ $store->address?->flat_no ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th> {{ __('Latitude') }}</th>
                                <td>{{ $store->address?->latitude ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Longitude') }}</th>
                                <td>{{ $store->address?->longitude ?? '--' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editAddressModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content ">
                        <div class="modal-header bg-secondary">
                            <h4 class="modal-title"> {{ __('Edit_Shop_Address') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('store.address.update', $store->id) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="modal-body py-2">
                                <div class="row">
                                    <div class="col-12">
                                        <label class="mb-0">{{ __('Address_Name') }}</label>
                                        <x-input type="text" name="address_name" :value="$store->address?->address_name" placeholder="Address Name"/>
                                    </div>
                                    <div class="col-12">
                                        <label class="mb-0">{{ __('Area') }}</label>
                                        <x-input type="text" name="area" :value="$store->address?->area" placeholder="Area"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="mb-0">{{ __('Road_No') }}:</label>
                                        <x-input type="text" name="road_no" :value="$store->address?->road_no" placeholder="Road No"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="mb-0">{{ __('Block') }}:</label>
                                        <x-input type="text" name="block" :value="$store->address?->block" placeholder="Block"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="mb-0">{{ __('House_No') }}:</label>
                                        <x-input type="text" name="house_no" :value="$store->address?->house_no" placeholder="House No"/>

                                    </div>
                                    <div class="col-sm-6">
                                        <label class="mb-0">{{ __('Flat_No') }}:</label>
                                        <x-input type="text" name="flat_no" :value="$store->address?->flat_no" placeholder="Flat No"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="mb-0">{{ __('Latitude') }}:</label>
                                        <x-input type="text" name="latitude" :value="$store->address?->latitude" placeholder="latitude"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="mb-0">{{ __('Longitude') }}:</label>
                                        <x-input type="text" name="longitude" :value="$store->address?->longitude" placeholder="longitude"/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-secondary">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">{{ __('Save_And_Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editUserModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content ">
                        <div class="modal-header bg-secondary">
                            <h4 class="modal-title">{{ __('Edit_Personal_Information') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('store.user.update', $store->user->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="modal-body py-2">
                                <label class="mb-0">{{ __('First_Name') }}</label>
                                <x-input type="text" name="first_name" :value="$store->user->first_name" />

                                <label class="mb-0">{{ __('Last_Name') }}</label>
                                <x-input type="text" name="last_name" :value="$store->user->last_name" />

                                <label class="mb-0">{{ __('Email_Address') }}</label>
                                <x-input type="email" name="email" :value="$store->user->email" />

                                <input type="hidden" name="phone" value="{{ $store->user->mobile }}">

                                <label class="mb-0">{{ __('Gender') }}</label>
                                <x-select name="gender">
                                    @foreach (config('enums.ganders') as $gender)
                                        <option value="{{ $gender }}"
                                            {{ $gender == $store->user->gender ? 'selected' : '' }}>
                                            {{ $gender }}
                                        </option>
                                    @endforeach
                                </x-select>

                                <label class="mb-0">{{ __('Profile_Photo') }}</label>
                                <x-input-file name="profile_photo" />
                            </div>
                            <div class="modal-footer bg-secondary">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">{{ __('Save_And_Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ mapApiKey() }}"></script>
    @if (!$store->latitude || !$store->longitude)
    <script>
        window.onload = function(){
            navigator.geolocation.getCurrentPosition(showPosition);
        };

        function showPosition(currentPosition) {
            $.ajax({
                url : '/profile/location',
                type : "put",
                dataType : "json",
                data: {
                    lat: currentPosition.coords.latitude,
                    lng: currentPosition.coords.longitude
                },
                success:function(data){}
            });
        }
    </script>
    @endif
<script>
    var position = [{{ $store->latitude }}, {{ $store->longitude }}];
    function initialize() {
        var latlng = new google.maps.LatLng(position[0], position[1]);
        var myOptions = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("mapCanvas"), myOptions);
        marker = new google.maps.Marker({
            position: latlng,
            map: map,
            title: "{{ $store->name }}",
            icon: "{{ asset('web/final-shop.png') }}"
        });

        google.maps.event.addListener(map, 'click', function(event) {
            var result = [event.latLng.lat(), event.latLng.lng()];
            transition(result);
            $('#lat').val(result[0])
            $('#lng').val(result[1])
        });
    }

    //Load google map
    google.maps.event.addDomListener(window, 'load', initialize);

    var numDeltas = 100;
    var delay = 1; //milliseconds
    var i = 0;
    var deltaLat;
    var deltaLng;

    function transition(result){
        i = 0;
        deltaLat = (result[0] - position[0])/numDeltas;
        deltaLng = (result[1] - position[1])/numDeltas;
        moveMarker();
    }

    function moveMarker(){
        position[0] += deltaLat;
        position[1] += deltaLng;


        var latlng = new google.maps.LatLng(position[0], position[1]);
        marker.setTitle("Latitude:"+position[0]+" | Longitude:"+position[1]);
        marker.setPosition(latlng);
        if(i!=numDeltas){
            i++;
            setTimeout(moveMarker, delay);
        }
    }
</script>
@endpush
