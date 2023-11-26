/*--- A P I - F O R - S E L E C T S ---*/
$(document).ready(function () {
    //-------------------------------SELECCIÓN EN CADENA-------------------------//
    // Variables para almacenar valores seleccionados de país, región y ciudad
    var selectedCountry = (selectedRegion = selectedCity = "");
    // Esta es una clave API de demostración con fines de prueba.
    // Reemplácela con su clave API real de http://battuta.medunes.net/
    var BATTUTA_KEY = "00000000000000000000000000000000";
    // URL para poblar la caja de selección de país desde la API de Battuta
    var url =
        "https://battuta.medunes.net/api/country/all/?key=" +
        BATTUTA_KEY +
        "&callback=?";
    // EXTRAER DATOS JSON.
    $.getJSON(url, function (data) {
        console.log(data);
        // Iterar a través de los datos recibidos y poblar la caja de selección de país
        $.each(data, function (index, value) {
            // AGREGAR O INSERTAR DATOS AL ELEMENTO DE SELECCIÓN.
            $("#country").append(
                '<option value="' + value.code + '">' + value.name + "</option>"
            );
        });
    });
    // País seleccionado --> actualizar lista de regiones.
    $("#country").change(function () {
        selectedCountry = this.options[this.selectedIndex].text;
        countryCode = $("#country").val();
        // Poblar la caja de selección de regiones desde la API de Battuta según el país seleccionado
        url =
            "https://battuta.medunes.net/api/region/" +
            countryCode +
            "/all/?key=" +
            BATTUTA_KEY +
            "&callback=?";
        $.getJSON(url, function (data) {
            $("#region option").remove();
            $('#region').append('<option value="">Por favor, selecciona tu región</option>');
            // Iterar a través de los datos recibidos y poblar la caja de selección de regiones
            $.each(data, function (index, value) {
                // AGREGAR O INSERTAR DATOS AL ELEMENTO DE SELECCIÓN.
                $("#region").append(
                    '<option value="' + value.region + '">' + value.region + "</option>"
                );
            });
        });
    });
    // Región seleccionada --> actualizar lista de ciudades
    $("#region").on("change", function () {
        selectedRegion = this.options[this.selectedIndex].text;
        countryCode = $("#country").val();
        region = $("#region").val();
        // Poblar la caja de selección de ciudades desde la API de Battuta según el país y la región seleccionados
        url =
            "https://battuta.medunes.net/api/city/" +
            countryCode +
            "/search/?region=" +
            region +
            "&key=" +
            BATTUTA_KEY +
            "&callback=?";
        $.getJSON(url, function (data) {
            console.log(data);
            $("#city option").remove();
            $('#city').append('<option value="">Por favor, selecciona tu ciudad</option>');
            // Iterar a través de los datos recibidos y poblar la caja de selección de ciudades
            $.each(data, function (index, value) {
                // AGREGAR O INSERTAR DATOS AL ELEMENTO DE SELECCIÓN.
                $("#city").append(
                    '<option value="' + value.city + '">' + value.city + "</option>"
                );
            });
        });
    });
    // Ciudad seleccionada --> actualizar cadena de ubicación
    $("#city").on("change", function () {
        selectedCity = this.options[this.selectedIndex].text;
        // Mostrar la ubicación seleccionada en el elemento HTML con id 'location'
        $("#location").html(
            "Ubicación: País: " +
            selectedCountry +
            ", Región: " +
            selectedRegion +
            ", Ciudad: " +
            selectedCity
        );
    });
});