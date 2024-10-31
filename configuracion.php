<?php
// Incluir el archivo que contiene la función de obtener registros
require_once('contador.php');

// Obtener el último registro en la tabla
$ultimo_registro = obtener_ultimo_registro();

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los valores de los campos del formulario
    $inicio_contador = $_POST['inicio-contador'];
    $tiempo_registro = $_POST['tiempo-registro'];

    // Actualizar los valores en la base de datos
    actualizar_configuracion($inicio_contador, $tiempo_registro);
}
// Obtener los valores actuales de la configuración
$configuracion = obtener_configuracion();
// print_r($configuracion);
// print_r($configuracion->id);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Configuración del Contador</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Agregar enlaces a archivos CSS y JS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-XI5zOVeQySUxzzAoZ6PhYD6JyyRJ2XZ94cP/VGIpHnDnFvCh3qzBn1itbYPrYhUv" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-mN0GhEK6T2QrbsobU6a1USeTJAtmK1mYDpeZVJbRgRZZ72X9FJ8H3qCnSzg+Dmn" crossorigin="anonymous"></script>
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

        .container-person {
            margin: 22px 20px;
        }

        div label {
            display: block;
            margin: 10px 5px;
            font-size: 14px;
            font-weight: 600;
        }

        button.btn.btn-primary {
            padding: 4px 10px;
            margin: 18px 0px;
            font-size: 14px;
            font-weight: 600;
            color: rebeccapurple;
        }

        div input[type='number'] {
            width: 68px;
            color: rebeccapurple;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="container container-person mt-5">
        <h1 class="mb-3">Configuración del Contador</h1>

        <!-- Mostrar mensaje de éxito si se ha guardado la configuración -->
        <?php if (isset($_GET['exito'])) : ?>
            <div class="alert alert-success" role="alert">
                Se ha guardado la configuración exitosamente.
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="inicio-contador" class="form-label">Valor de inicio del contador</label>
                <input type="number" class="form-control" id="inicio-contador" name="inicio-contador" value="<?php if (isset($configuracion) && $configuracion) {
                                                                                                                    echo $configuracion->contador;
                                                                                                                }
                                                                                                                ?>">
            </div>
            <div class="mb-3">
                <label for="tiempo-registro" class="form-label">Tiempo para hacer un nuevo registro (en minutos) (ingrese un número)</label>
                <input type="number" class="form-control" id="tiempo-registro" name="tiempo-registro" value="<?php if (isset($configuracion) && $configuracion) {
                                                                                                                    echo $configuracion->tiempo_espera;
                                                                                                                }
                                                                                                                ?>" min="1">
            </div>
            <button type="submit" class="btn btn-primary">Guardar configuración</button>
        </form>
    </div>

</body>

</html>