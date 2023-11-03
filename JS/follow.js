$(document).ready(function() {
    $(".follow-button").click(function() {
        var boton = $(this);
        var usuario_id = boton.data("usuario-id");

        $.ajax({
            url: '../Clases/seguidor-seguido.php?Seguidor_Seguido',
            type: 'POST',
            data: { usuario_id: usuario_id }, // Envía solo usuario_id
            success: function (response) {
                response = JSON.parse(response); // Parsea la respuesta JSON
                if (response.success) {
                    console.log(response.message);
                    boton.text("Seguido").attr("disabled", true).css("color", "#000000");
                } else {
                    console.log(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX: " + error);
            }
        });
    });
});

