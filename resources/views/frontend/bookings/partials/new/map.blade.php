{{-- Map Loading --}}
<div class="map-booking">
    <div id="map" style="height: 400px; width:100%;"></div>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap"
        async defer></script>
</div>
