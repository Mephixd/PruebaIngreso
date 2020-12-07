@extends('layouts.layouts')

@section('content')

    <label>
        <h4>Datos</h4>
        <form id="formRut">
            @csrf
            <div class="form-group">
                <label for="usr">Ingrese Rut:</label>
                <input type="text" class="form-control" id="buscarRut">
            </div>
        </form>
    </label>

    <table id="tablaPersonas" class="display">
        <thead>
            <tr>
                <th>RUT</th>
                <th>Razón social</th>
                <th>Actividades</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datoPersona as $persona)
                <tr>
                    <td>
                        {{ $persona->rut }}
                    </td>
                    <td>
                        {{ $persona->razon_social }}
                    </td>
                    <td>
                        {{ $persona->actividades[0] }}
                    </td>
                    <td>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modalEditarPersona">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Modificar datos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="usr">RUT:</label>
                                    <input type="text" class="form-control" id="inputRutEdit" style="border-radius: 50px" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="usr">Razón social:</label>
                                    <input type="text" class="form-control" id="inputRSEdit" style="border-radius: 50px">
                                </div>
                                <div class="form-group">
                                    <label for="usr">Actividades:</label>
                                    <textarea class="form-control" rows="5" id="inputActEdit" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="display: flex;align-items: center;justify-content: center;">
                    <button type="submit" class="btn btn-secondary botoness" id="btnguardarPersona" style="width: 100px; border-radius: 50px;">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            listarPersonas();
        });

        $('#formRut').submit(function(e) {
            e.preventDefault();
            var buscarRut = $('#buscarRut').val();

            $.ajax({
                type: "get",
                url: "/mostrarDatos/" + buscarRut,
                success: function(response) {
                    var resRut = response.rut;
                    var resRazonSocial = response.razon_social;
                    if (response.actividades.length > 0) {
                        var resActividades = response.actividades[0].giro;
                        var resActividadesCodigo = response.actividades[0].codigo;
                    } else {
                        var resActividades = "sin actividad";
                        var resActividadesCodigo = "sin código";
                    }

                    $.ajax({
                        type: "post",
                        url: "{{ route('enviarDatos') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'resRut': resRut,
                            'resRazonSocial': resRazonSocial,
                            'resActividades': resActividades,
                            'resActividadesCodigo': resActividadesCodigo,
                        },
                        success: function(response) {
                            if(response==true){
                                alert('Guardado con éxito.');
                            }else{
                                alert('¡Error! El usuario ya existe.');
                            }
                            location.reload();
                            //no pude hace que se refrescara la tabla :(
                            /* table.ajax.reload(); */
                        }
                    });
                }
            });
        });


        var listarPersonas = function() {
            var table = $('#tablaPersonas').DataTable({
                lengthMenu: [
                    [10, 30, 50, -1],
                    [10, 30, 50, "Todo"]
                ],
                "searching": false,
                destroy: true,
                ordentable: true,
                dom: 'Bfrtip',
                responsive: true,
                language: {
                    "decimal": "",
                    "emptyTable": "No hay información",
                    "info": " _START_ - _END_ de _TOTAL_ ",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ Entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "buttons": [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                "columns": [{
                        "data": "rut"
                    },
                    {
                        "data": "razon_social"
                    },
                    {
                        "data": "actividades"
                    },
                    {
                        "searchable": false,
                        "orderable": false,
                        "render": function(data, type, row, full) {
                            return `<button type='button' id="btnEditarPersona" data-toggle="modal"  data-target="#modalEditarPersona" class='btn btn-warning modaleditarPersona'>Editar</button>  
                                <button type='button' id="botonEliminar" class='btn btn-danger elimnarr'>Eliminar</button>`
                        }
                    }
                ]
            });
            funMostrarModal('#tablaPersonas tbody', table);
            funEliminarFila('#tablaPersonas tbody', table);
        }

        var funMostrarModal = function(tbody, table){
            $(tbody).on('click','button.modaleditarPersona', function(){
                var data = table.row($(this).parents('tr')).data();
                $('#inputRutEdit').val(data.rut);
                $('#inputRSEdit').val(data.razon_social);
                $('#inputActEdit').val(data.actividades);
            });
        }

        var funEliminarFila = function(tbody, table){
            $(tbody).on('click','button.elimnarr', function(){
                var data = table.row($(this).parents('tr')).data();
                console.log(data.rut);
                $.ajax({
                    type: "post",
                    url: "{{route('elminarFila')}}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "rut":data.rut,
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            });
        }

        $('#btnguardarPersona').on('click',function(e){
            e.preventDefault();
            var inputRutEdit = $('#inputRutEdit').val();
            var inputRSEdit = $('#inputRSEdit').val();
            var inputActEdit = $('#inputActEdit').val();

            $.ajax({
                type: "post",
                url: "{{route('editarDatos')}}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'inputRutEdit': inputRutEdit,
                    'inputRSEdit': inputRSEdit,
                    'inputActEdit': inputActEdit,
                },
                success: function (response) {
                    location.reload();
                }
            });
        })
    </script>


@endsection
