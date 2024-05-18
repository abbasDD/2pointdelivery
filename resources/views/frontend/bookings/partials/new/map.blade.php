{{-- Map Loading --}}
<div class="map-booking">
    <div id="map" style="height: 400px; width:100%;"></div>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-jXtk8qCpcwUwFn-7Q3VazeneJJ46g00&libraries=places&callback=initMap"
        async defer></script>
    {{-- Load mapjs script here --}}
    @include('frontend.bookings.js.map')
</div>
