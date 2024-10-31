<?php
/*
Plugin Name: My Contador lesr

Plugin URI: https://nes360.org/

Description:¡Gracias por descargar nuestro plugin! Con más de 5000 descargas, estamos emocionados de ver cómo ha ayudado a nuestros usuarios a llevar un seguimiento preciso de sus visitantes.<br><br> Este plugin es para contar ingresos de personas a determinada landig sea página o post dentro de wordpress, se maneja de un modo sencillo donde permite contar de un numero de terminado en adelante, para asignar el número de inicio se debe ingresar a la edición del plugin y ponerlo antes de instalarlo o espesara a contar desde el número 0000000001, el plugin registra la ip y la fecha en la que se crea el conteo y no permite realizar un nuevo conteo hasta después de 5 minutos, esto lo puedes seguir en la administración del plugin que tiene su botón principal en el menú principal de la administración de wordpress.<br><br>Nos complace anunciar que hemos agregado nuevas funcionalidades para hacer que la experiencia de seguimiento sea aún más completa. Ahora puedes descargar un informe detallado en formato CSV para un análisis más profundo, y también hemos agregado una interfaz de configuración para personalizar la configuración de conteo.<br><br>Descarga nuestro plugin ahora y experimenta la diferencia en cómo puedes obtener información valiosa sobre tus visitantes de manera fácil y eficiente. ¡Gracias de nuevo por elegir nuestro plugin y esperamos que lo disfrutes!

This is a plugin to tell people income is determined Landig page or post in wordpress this is handled in a simple manner which allows for a number of finished onwards to assign the number must be entered to start editing plugin and put it before installing or thickened starting from the number 9870000000, the plug records the ip and the date on which the count is created and does not allow a recount until after 5 minutes, this will pudes follow administration plugin which has its main button on the main menu of WordPress administration.

Depending on your will download a new version soon.

Version: 2.0

Author: Luis Erasmo Suarez 

Author URI: https://nes360.org/

License: GPL2

  Copyright 2015 and Copyright 2023 luis Erasmo Suarez Rondon  (email : ti@nes360.org)



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License, version 2, as 

    published by the Free Software Foundation.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Función para contar visitas y mostrar shortcode
function codigocorto_funcion($atts)
{

  global $wpdb;

  $table_name = $wpdb->prefix . "contar";
  $table_name_config = $wpdb->prefix . "config_contar";

  $ip = $_SERVER['REMOTE_ADDR'];
  $etiqueta = isset($atts['etiqueta']) ? $atts['etiqueta'] : '';
  $tiempo = current_time('mysql', 1); //obtener la hora actual del sitio en formato MySQL

  // Obtener el último registro de la tabla
  $last_row = $wpdb->get_row("SELECT * FROM $table_name ORDER BY id DESC LIMIT 1");
  $last_config = $wpdb->get_row("SELECT * FROM $table_name_config ORDER BY id DESC LIMIT 1");

  // Obtener los valores del último registro
  $ultimaIpRegistrada = $last_row->ip;
  $ultimaFechaRegistrada = $last_row->fecha;
  $ultimaCuentaRegistrada = $last_row->contador;

  $tiempo_espera = $last_config->tiempo_espera;

  if ($ip == $ultimaIpRegistrada && (strtotime($tiempo) - strtotime($ultimaFechaRegistrada) < $tiempo_espera * 60)) {
    // Si la IP es la misma y ha pasado menos de $tiempo_espera minutos desde el último registro
    $respuesta = 'Si ya ha terminado su transacción, por favor espere ' . $tiempo_espera . ' minutos antes de iniciar otra';
  } else {
    // Si la IP es diferente o ha pasado más de $tiempo_espera minutos desde el último registro
    $suma = $ultimaCuentaRegistrada + 1;
    $codigo = $etiqueta . $suma;
    $respuesta = "Su código de transacción es: <span id='codigoG'>$codigo</span>";
    $wpdb->insert($table_name, array('contador' => $suma, 'ip' => $ip, 'fecha' => $tiempo, 'etiqueta' => $etiqueta));
  }

  return $respuesta;
}

add_shortcode('contar', 'codigocorto_funcion');

function mostrar_registros()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "contar";
  $registros = $wpdb->get_results("SELECT * FROM `" . $table_name . "` ORDER BY fecha DESC");
  return $registros;
}

// Función para incluir archivo con registros
function menu_pagina_mostras_registros()
{
  // Incluir archivo con función para mostrar registros
  include(plugin_dir_path(__FILE__) . 'mostrar-registros.php');
}
function menu_pagina_configuracion()
{
  // Incluir archivo con HTML de la nueva página
  include(plugin_dir_path(__FILE__) . 'configuracion.php');
}

// Agregar página al menú de administración de WordPress
function agregar_pagina()
{
  add_menu_page('Contador LESR', 'Contador LESR', 'manage_options', 'contador-lista', 'menu_pagina_mostras_registros');

  // Agregar subpágina con HTML propio
  add_submenu_page('contador-lista', 'Contador LESR Configuracion', 'Configuracion', 'manage_options', 'configuracion', 'menu_pagina_configuracion');
}

add_action('admin_menu', 'agregar_pagina');


function obtener_ultimo_registro()
{
  global $wpdb; // Conexión a la base de datos de WordPress
  $tabla_registros = $wpdb->prefix . 'contar'; // Nombre de la tabla de registros

  // Obtener el último registro de la tabla ordenando por la fecha de forma descendente
  $query = "SELECT * FROM $tabla_registros ORDER BY fecha DESC LIMIT 1";
  $resultado = $wpdb->get_row($query);

  return $resultado;
}

function nuevo_registro($nuevo_contador)
{
  global $wpdb;

  $tabla_contador = $wpdb->prefix . 'contador';
  $ultimo_registro = obtener_ultimo_registro();

  // Verificar si el nuevo contador es igual al contador del último registro
  if ($nuevo_contador != $ultimo_registro['contador']) {
    $nuevo_contador = $ultimo_registro['contador'] + 1;
    $ip = 'resetip-190.00.00.000';
    $etiqueta = 'reiniciar numeración de contador';

    $nuevo_registro = array(
      'contador' => $nuevo_contador,
      'ip' => $ip,
      'etiqueta' => $etiqueta,
      'fecha' => current_time('mysql')
    );

    $wpdb->insert($tabla_contador, $nuevo_registro);
  }
}

add_action('admin_init', 'exportar_registros');

function exportar_registros()
{
  if (isset($_GET['action']) && $_GET['action'] == 'exportar_registros') {
    $filename = 'registros.csv';
    $header_row = array('ID', 'IP', 'Etiqueta', 'Contador', 'Fecha');
    $registros = get_registros();

    // Crear el archivo CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=' . $filename);
    $file = fopen('php://output', 'w');
    fputcsv($file, $header_row);

    // Agregar cada registro al archivo CSV
    foreach ($registros as $registro) {
      fputcsv($file, $registro);
    }

    fclose($file);
    exit();
  }
}

function get_registros()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "contar";
  $registros = array();

  $results = $wpdb->get_results("SELECT id, ip, etiqueta, contador, fecha FROM $table_name ORDER BY contador DESC");

  if (!empty($results)) {
    foreach ($results as $registro) {
      $registros[] = array(
        $registro->id,
        $registro->ip,
        $registro->etiqueta,
        $registro->contador,
        $registro->fecha
      );
    }
  }

  return $registros;
}

function obtener_configuracion() {
  global $wpdb;

  $table_name_config = $wpdb->prefix . "config_contar";

  // Obtener el último registro de la tabla
  $last_config = $wpdb->get_row("SELECT * FROM $table_name_config ORDER BY id DESC LIMIT 1");

  return $last_config;
}


function actualizar_configuracion($inicio_contador = null, $tiempo_registro = null) {
  global $wpdb;

  $table_name = $wpdb->prefix . 'config_contar';

  // Obtener la configuración actual
  $configuracion = obtener_configuracion();

  if (!$configuracion) {
      // Si no existe registro en la tabla, insertar uno nuevo
      $wpdb->insert(
          $table_name,
          array(
              'contador' => $inicio_contador,
              'tiempo_espera' => $tiempo_registro,
          )
      );
  } else {
      // Si ya existe un registro en la tabla, actualizar los valores
      if ($inicio_contador !== null) {
          $wpdb->update(
              $table_name,
              array('contador' => $inicio_contador),
              array('id' => $configuracion->id)
          );
      }
      if ($tiempo_registro !== null) {
          $wpdb->update(
              $table_name,
              array('tiempo_espera' => $tiempo_registro),
              array('id' => $configuracion->id)
          );
      }
  }
}

/*instalacion del contador*/
function contar_instala() {
  global $wpdb;

  $table_name = $wpdb->prefix . "contar";
  $table_name_config = $wpdb->prefix . "config_contar";
  // Verificar si la tabla ya existe en la base de datos
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
    return;
  }

  // Crear la tabla
  $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            contador bigint(30) NOT NULL,
            ip varchar(120) NOT NULL,
            etiqueta varchar(120) NULL,
            fecha datetime NOT NULL,
            PRIMARY KEY (id)
          ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

  $wpdb->query($sql);

  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_config'") == $table_name_config) {
    return;
  }

  $sql_config = "CREATE TABLE $table_name_config (
    id int(11) NOT NULL AUTO_INCREMENT,
    contador bigint(30) NOT NULL,
    tiempo_espera int(11) NOT NULL,
    PRIMARY KEY (id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

$wpdb->query($sql_config);

  // Insertar el primer registro con el contador inicial
  $ip = '190.00.00.000';
  $etiqueta = 'registro-inicio-conteo';
  $fecha = current_time('mysql');
  $contador = '0000000001';
  $tiempo_espera = '5';

  $nuevo_registro = array(
    'contador' => $contador,
    'ip' => $ip,
    'etiqueta' => $etiqueta,
    'fecha' => $fecha
  );

  $wpdb->insert($table_name, $nuevo_registro);

  $nuevo_registro_config = array(
    'contador' => $contador,
    'tiempo_espera' => $tiempo_espera,
  );

  $wpdb->insert($table_name_config, $nuevo_registro_config);
}

/*desinstalacion del contador*/
function contar_desinstala()
{

  global $wpdb;

  $tabla_nombre = $wpdb->prefix . "contar";

  $sql = "DROP TABLE $tabla_nombre";

  $wpdb->query($sql);
}

register_activation_hook(__FILE__, 'contar_instala');

register_deactivation_hook(__FILE__, 'contar_desinstala');



add_action('activate_contar/contador.php', 'contar_instala');

add_action('deactivate_contar/contador.php', 'contar_desinstala');
