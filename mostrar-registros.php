<?php
// Incluir el archivo que contiene la función de obtener registros
require_once('contador.php');

// Obtener registros
$registros = mostrar_registros();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registros de contador</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Agregar enlaces a archivos CSS y JS de Bootstrap y DataTables -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive {
            margin: 40px 0px;
        }

        .container-person {
            padding: 28px 0px;
        }

        .dataTables_length select {
            width: 51px;
        }
    </style>
</head>

<body>

    <div class="container container-person">
        <h1>Registros de contador</h1>
        <?php
        //print_r($registros);
        ?>
        <p>recuerde ejecutar su codigo corto [contar etiqueta="Pago"]</p>
        <!-- Agregar tabla con registros -->
        <div class="table-responsive">
            <table id="registros" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>IP</th>
                        <th>Contador</th>
                        <th>Etiqueta</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro) : ?>
                        <tr>
                            <td><?php echo $registro->ip; ?></td>
                            <td><?php echo $registro->contador; ?></td>
                            <td><?php echo $registro->etiqueta; ?></td>
                            <td><?php echo $registro->fecha; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Agregar botón para descargar registros en formato CSV -->
        <a href="<?php echo esc_url(admin_url('admin.php')); ?>?action=exportar_registros" class="button btn-info">Exportar a CSV</a>

    </div>

    <!-- Agregar script para inicializar DataTables en la tabla -->
    <script>
        $(document).ready(function() {
            $('#registros').DataTable();
        });
    </script>

</body>

</html>