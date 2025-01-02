@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-body">
                        <div id="googleMap" style="width: 100%; height: 85vh;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ mapApiKey() }}"></script>

    <script>
        $(document).ready(function() {
            getLocation()
        })

        function getLocation() {
            navigator.geolocation.getCurrentPosition(showPosition);
        }

        function showPosition(currentPosition) {
            localStorage.removeItem('lat');
            localStorage.removeItem('lng');
            localStorage.setItem('lat', currentPosition.coords.latitude);
            localStorage.setItem('lng', currentPosition.coords.longitude);
        }
    </script>

    <script type="text/javascript">
        $.ajax({
            url: '/shop/locations',
            type: "get",
            dataType: "json",
            success: function(data) {
                var locations = data;

                var map = new google.maps.Map(document.getElementById('googleMap'), {
                    zoom: 16,
                    center: new google.maps.LatLng(localStorage.getItem('lat'), localStorage.getItem(
                        'lng')),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infowindow = new google.maps.InfoWindow();

                var marker, i;

                for (i = 0; i < locations.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
                        icon: "{{ asset('web/final-shop.png') }}",
                        map: map
                    });

                    let url = `shops/${locations[i]['id']}/details`;

                    let details = `
                    Name: <a href="{{ url('${url}') }}"><strong style="color:blue">${locations[i]['name']}</strong></a><br>
                    Email: <strong>${locations[i]['email']}</strong><br>
                    Phone: <strong>${locations[i]['phone']}</strong><br>
                    Rating: <span style="color:#fbb340">&#9733</span>
                            <span style="color:#fbb340">&#9733</span>
                            <span style="color:#fbb340">&#9733</span>
                            <span style="color:#fbb340">&#9733</span>
                            <span style="color:#fbb340">&#9733</span>`

                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() {
                            infowindow.setContent(details);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            }
        });
    </script>
@endpush
