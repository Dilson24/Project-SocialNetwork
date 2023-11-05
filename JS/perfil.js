// Función para abrir un popup
function openPopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "flex";
        popup.classList.add("active");
    }
}

// Función para cerrar el popup
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = "none";
        popup.classList.remove("active");
    }
}

// Función para cambiar el aspecto del botón Unfollow
function followBtn(button) {
    button.textContent = 'Seguir';
    button.classList.remove("btnUnfollow");
    button.classList.add("btnFollow");
}

// Agregar eventos y controladores de clic
document.addEventListener("DOMContentLoaded", function () {
    const showFollowers = document.querySelector(".info-followers");
    showFollowers.addEventListener("click", function () {
        openPopup("showFollowers");
    });

    const closeFollowers = document.getElementById("close_Followers");
    closeFollowers.addEventListener("click", function () {
        closePopup("showFollowers");
    });

    const showFollowing = document.querySelector(".info-following");
    showFollowing.addEventListener("click", function () {
        openPopup("showFollowings");
    });

    const closeFollowing = document.getElementById("close_Following");
    closeFollowing.addEventListener("click", function () {
        closePopup("showFollowings");
    });
    //Manejo de solioitudes
    // Logout Button
    var logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", '../Clases/usuario.php?logout', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    window.location.href = '../index.php';
                }
            };
            xhr.send();
        });
    }
    // // Unfollow Button
    // document.querySelectorAll(".btnUnfollow").forEach(function (button) {
    //     button.addEventListener("click", function () {
    //         // Obtener el ID del usuario a dejar de seguir
    //         var seguidos_id = parseInt(this.getAttribute("data-id"));

    //         // Crear un objeto FormData para enviar los datos
    //         var formData = new FormData();
    //         formData.append('usuario_id', seguidos_id);

    //         // Crear una solicitud AJAX
    //         var xhr = new XMLHttpRequest();
    //         xhr.open('POST', '../Clases/seguidor-seguido.php?Unfollow', true);

    //         // Configurar el encabezado necesario
    //         xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    //         // Manejar la respuesta de la solicitud
    //         xhr.onload = function () {
    //             if (xhr.status >= 200 && xhr.status < 300) {
    //                 try {
    //                     var response = JSON.parse(xhr.responseText);
    //                     if (response.success) {
    //                         // Actualizar el elemento HTML con el nuevo total de seguidos
    //                         var totalFollowingsElement = document.querySelector(".info-following span");
    //                         totalFollowingsElement.textContent = response.total_seguidos;
    //                         console.log(response);
    //                         followBtn(button);
    //                     } else {
    //                         console.log("Error al dejar de seguir al usuario");
    //                     }
    //                 } catch (e) {
    //                     console.log("Error al analizar la respuesta JSON");
    //                 }
    //             } else {
    //                 console.log("Error en la solicitud: " + xhr.status);
    //             }
    //         };

    //         // Enviar los datos del formulario
    //         xhr.send(formData);
    //     });
    // });

    // // Follow button
    // var container = document.getElementById("following");

    // container.addEventListener("click", function (event) {
    //     var btnFollowElements = container.querySelectorAll(".btnFollow");
    //     btnFollowElements.forEach(function (element) {
    //         if (event.target === element) {
    //             var usuario_id = element.getAttribute("data-id");
    //             var xhr = new XMLHttpRequest();
    //             xhr.open("POST", '../Clases/seguidor-seguido.php?Seguidor_Seguido', true);
    //             xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //             xhr.onreadystatechange = function () {
    //                 if (xhr.readyState === 4) {
    //                     if (xhr.status === 200) {
    //                         var response = JSON.parse(xhr.responseText);
    //                         if (response.success) {
    //                             element.classList.remove("btnFollow");
    //                             element.classList.add("btnUnfollow");
    //                             element.textContent = "Dejar de seguir";
    //                             console.log(response.message);
    //                         } else {
    //                             console.log(response.message);
    //                         }
    //                     } else {
    //                         console.error("Error en la solicitud AJAX: " + xhr.statusText);
    //                     }
    //                 }
    //             };
    //             xhr.send("usuario_id=" + usuario_id);
    //         }
    //     });
    // });
    //  // Unfollow Button
    //  document.querySelectorAll(".btnUnfollow").forEach(function (button) {
    //     button.addEventListener("click", function () {
    //         // Obtener el ID del usuario a dejar de seguir
    //         var seguidos_id = parseInt(this.getAttribute("data-id"));

    //         // Crear un objeto FormData para enviar los datos
    //         var formData = new FormData();
    //         formData.append('usuario_id', seguidos_id);

    //         // Crear una solicitud AJAX
    //         var xhr = new XMLHttpRequest();
    //         xhr.open('POST', '../Clases/seguidor-seguido.php?Unfollow', true);

    //         // Configurar el encabezado necesario
    //         xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    //         // Manejar la respuesta de la solicitud
    //         xhr.onload = function () {
    //             if (xhr.status >= 200 && xhr.status < 300) {
    //                 try {
    //                     var response = JSON.parse(xhr.responseText);
    //                     if (response.success) {
    //                         // Actualizar el elemento HTML con el nuevo total de seguidos
    //                         var totalFollowingsElement = document.querySelector(".info-following span");
    //                         totalFollowingsElement.textContent = response.total_seguidos;
    //                         console.log(response);
    //                         followBtn(button);
    //                     } else {
    //                         console.log("Error al dejar de seguir al usuario");
    //                     }
    //                 } catch (e) {
    //                     console.log("Error al analizar la respuesta JSON");
    //                 }
    //             } else {
    //                 console.log("Error en la solicitud: " + xhr.status);
    //             }
    //         };

    //         // Enviar los datos del formulario
    //         xhr.send(formData);
    //     });
    // });

    // // Follow button
    // var container = document.getElementById("following");

    // container.addEventListener("click", function (event) {
    //     var btnFollowElements = container.querySelectorAll(".btnFollow");
    //     btnFollowElements.forEach(function (element) {
    //         if (event.target === element) {
    //             var usuario_id = element.getAttribute("data-id");
    //             var xhr = new XMLHttpRequest();
    //             xhr.open("POST", '../Clases/seguidor-seguido.php?Seguidor_Seguido', true);
    //             xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //             xhr.onreadystatechange = function () {
    //                 if (xhr.readyState === 4) {
    //                     if (xhr.status === 200) {
    //                         var response = JSON.parse(xhr.responseText);
    //                         if (response.success) {
    //                             element.classList.remove("btnFollow");
    //                             element.classList.add("btnUnfollow");
    //                             element.textContent = "Dejar de seguir";
    //                             console.log(response.message);
    //                         } else {
    //                             console.log(response.message);
    //                         }
    //                     } else {
    //                         console.error("Error en la solicitud AJAX: " + xhr.statusText);
    //                     }
    //                 }
    //             };
    //             xhr.send("usuario_id=" + usuario_id);
    //         }
    //     });
    // });
    // Función para manejar el seguimiento (Follow/Unfollow) de un usuario
    function toggleFollow(button) {
        // Obtiene el ID del usuario desde el atributo "data-id" del botón
        var usuario_id = button.getAttribute("data-id");

        // Determina si se debe realizar un seguimiento o dejar de seguir
        var action = button.classList.contains("btnUnfollow") ? "Unfollow" : "Follow";

        // Crea una solicitud AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", '../Clases/seguidor-seguido.php?' + action, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Maneja la respuesta de la solicitud
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Actualiza el botón y el total de seguidos en la interfaz
                        var totalFollowingsElement = document.querySelector(".info-following span");
                        totalFollowingsElement.textContent = response.total_seguidos;
                        if (action === "Unfollow") {
                            // Si se estaba siguiendo al usuario, cambia el botón y el texto
                            button.classList.remove("btnUnfollow");
                            button.classList.add("btnFollow");
                            button.textContent = "Seguir";
                        } else {
                            // Si no se estaba siguiendo al usuario, cambia el botón y el texto
                            button.classList.remove("btnFollow");
                            button.classList.add("btnUnfollow");
                            button.textContent = "Dejar de seguir";
                        }
                        console.log(response.message);
                    } else {
                        console.log("Error al cambiar el estado de seguimiento");
                    }
                } else {
                    console.error("Error en la solicitud AJAX: " + xhr.statusText);
                }
            }
        };

        // Envía el ID del usuario al servidor
        xhr.send("usuario_id=" + usuario_id);
    }

    // Agregar evento de clic a los botones de seguimiento (Follow/Unfollow)
    document.querySelectorAll(".btnFollow, .btnUnfollow").forEach(function (button) {
        // Cuando se hace clic en un botón, llama a la función toggleFollow
        button.addEventListener("click", function () {
            toggleFollow(this);
        });
    });



});

