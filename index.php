<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD con PHP, AJAX, PDO y DataTables.js</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

</head>

<body>
    <div class="container containerBg">
        <h1 class="text-center">CRUD con PHP, AJAX y DataTables.js</h1>
        <div class="row">
            <div class="col-2 offset-10">
                <div class="text-center">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#modalUsuario" id="botonCrear">
                        <i class="bi bi-plus-circle-fill"></i>
                        Crear
                    </button>
                </div>
            </div>
        </div>

        <br>
        <br>

        <div class="table-responsive">
            <table id="datos_usuario" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Imagen</th>
                        <th>Fecha creación</th>
                        <th>Editar</th>
                        <th>Borrar</th>
                    </tr>
                </thead>

            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalUsuarioLabel">Crear usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="" id="formulario" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-body">
                            <label for="nombre">Ingrese el nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control">
                            <br>
                            <label for="apellido">Ingrese el apellido</label>
                            <input type="text" name="apellido" id="apellido" class="form-control">
                            <br>
                            <label for="telefono">Ingrese el teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control">
                            <br>
                            <label for="email">Ingrese el email</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <br>
                            <label for="imagen">Seleccione una imagen</label>
                            <input type="file" name="imagen_usuario" id="imagen_usuario" class="form-control">
                            <span id="imagen_subida"></span>
                            <br>
                        </div>

                        <div class="modal-footer">
                            <input type="hidden" name="id_usuario" id="id_usuario">
                            <input type="hidden" name="operacion" id="operacion">
                            <input type="submit" value="Crear" name="action" id="action" class="btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#botonCrear").click(function() {
                $("#formulario")[0].reset();
                $(".modal-title").text("Crear Usuario");
                $("#action").val("Crear");
                $("#operacion").val("Crear");
                $("#imagen_subida").html("");
            });

            var dataTable = $('#datos_usuario').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    url: "obtener_registros.php",
                    type: "POST"
                },
                "columnsDefs": [{
                    "targets": [0, 3, 4],
                    "orderable": false,
                }]
            });

            $(document).on('submit', '#formulario', function(event) {
                event.preventDefault();
                var nombre = $("#nombre").val();
                var apellido = $("#apellido").val();
                var telefono = $("#telefono").val();
                var email = $("#email").val();
                var extension = $("#imagen_usuario").val().split('.').pop().toLowerCase();

                if (extension != '') {
                    if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
                        alert("Formato de imagen inválido");
                        $("#imagen_usuario").val("");
                        return false;
                    }
                }

                // validar campos vacios
                if (nombre != '' && apellido != '' && email != '') {
                    $.ajax({
                        url: "crear.php",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false, //falso para poder subir img
                        processData: false,
                        success: function(data) {
                            alert(data);
                            $('#formulario')[0].reset();
                            $('#modalUsuario').modal('hide');
                            dataTable.ajax.reload();
                        }
                    });
                } else {
                    alert("Algunos campos son obligatorios");
                }
            });


            // Funcionalidad para editar registro
            $(document).on('click', '.editar', function(event) {
                var id_usuario = $(this).attr("id");
                $.ajax({
                    url: "obtener_registro.php",
                    method: "POST",
                    data: {
                        id_usuario: id_usuario
                    },
                    dataType: "json",
                    success: function(data) {
                        // console.log(data);
                        $('#modalUsuario').modal('show');
                        $('#nombre').val(data.nombre);
                        $('#apellido').val(data.apellido);
                        $('#telefono').val(data.telefono);
                        $('#email').val(data.email);
                        $('.modal-title').text("Editar Usuario");
                        $('#id_usuario').val(id_usuario);
                        $('#imagen_subida').html(data.imagen_usuario);
                        $('#action').val("Editar");
                        $('#operacion').val("Editar");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });
            });

            // FUNCIONALIDAD PARA BORRAR REGISTRO
            $(document).on('click', '.borrar', function(event) {
                var id_usuario = $(this).attr("id");
                if (confirm("Esta seguro de borrar este registro? : " + id_usuario)) {
                    $.ajax({
                        url: "borrar.php",
                        method: "POST",
                        data: {
                            id_usuario: id_usuario
                        },
                        success: function(data) {
                            alert(data);
                            dataTable.ajax.reload();
                        }
                    });
                }else{
                    return false;
                }
            });

            // fin document.ready
        });
    </script>
</body>

</html>