let geoAutocomplete;
let cityInput = $('#city-hidden-input');
let cityValue = '';

function initGeoSearch() {
    const input = document.getElementById('google-location-search');
    geoAutocomplete = new google.maps.places.Autocomplete(input, {
        'fields': ['address_components', 'geometry', 'place_id', 'formatted_address'],
        'types':  ['(cities)'],
    });
    geoAutocomplete.addListener('place_changed', parseGeoData);
    input.onchange = () => {
        input.value = '';
        cityValue = '';
        cityInput.val(cityValue);
    }
}

function parseGeoData() {
    const place = geoAutocomplete.getPlace();
    place.address_components.forEach((item) => {
        const type = item.types.shift();
        if (['locality', 'administrative_area_level_1', 'country'].includes(type)) {
            cityValue += type + '=' + item.long_name + ';';
        }
    });
    cityValue += 'placeId=' + place.place_id + ';';
    cityValue += 'lat=' + place.geometry.location.lat() + ';';
    cityValue += 'lng=' + place.geometry.location.lng() + ';';
    cityValue += 'fullName=' + place.formatted_address + ';';
    cityInput.val(cityValue);
}
