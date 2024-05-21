{{-- Map Loading --}}
<div class="map-booking">
    <div id="map" style="height: 400px; width:100%;"></div>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-oZSu4Kvv97DDpLZA20a9qIGMpwjtitM&libraries=places&callback=initMap"
        async defer></script>
    {{-- Load mapjs script here --}}
    @include('frontend.bookings.js.map')
</div>
