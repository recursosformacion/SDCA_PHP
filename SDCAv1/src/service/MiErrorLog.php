<?php
set_error_handler("gestionLog");
defined("DEBUG") or define("DEBUG", 0);
defined("SSH_PASSWORD") or define("SSH_PASSWORD", "");
defined("SSH_USUARIO") or define("SSH_USUARIO", "");
defined("SSH_HOST_DESTINO") or define("SSH_HOST_DESTINO", "");


if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    // $ruta=realpath(dirname(dirname(dirname(__FILE__)))).CD;
    $ruta = "D:\\AreaTrabajo\\";
    defined("LOG_RUTA") or define('LOG_RUTA', $ruta . 'logs\\');
    $conSudo="";
} else {
    defined("LOG_RUTA") or define('LOG_RUTA', '/var/tmp/');
    $conSudo="";
}
defined("LOG_NOMBRE_FICHERO") or define('LOG_NOMBRE_FICHERO', LOG_RUTA . "php_" . date("y-m-d") . ".log");
defined("LOG_DIAS_PERMANENCIA") or define('LOG_DIAS_PERMANENCIA', 30);

// trigger_error("Inicio Log", E_USER_NOTICE);
function logKioskoWeb($mensaje, $request)
{
    if (DEBUG && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        var_dump($mensaje);
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // correccion por CloudFlare
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $men = $_SERVER['REMOTE_ADDR'] . ' - ' . $mensaje . ' - ' . "Query:" . $_SERVER['QUERY_STRING'];
    gestionLog(0, $men, $_SERVER['SCRIPT_FILENAME'], 0);
}

function logKioskoWebError($mensaje, $request, $e)
{
    if (DEBUG && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        var_dump($e);
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // correccion por CloudFlare
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $men = $_SERVER['REMOTE_ADDR'] . ' - ' . $mensaje . ' - ' . "Query:" . $_SERVER['QUERY_STRING'];
    return gestionLog(E_USER_ERROR, $men, $_SERVER['SCRIPT_FILENAME'], $e->getLine());
}

function logKiosko($mensaje, $firma = "")
{
    if (DEBUG && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        var_dump($firma);
    $men = $mensaje;
    return gestionLog(99999, $men, $firma, 0);
}

/**
 * llega un $e
 *
 * @param
 *            $trace
 */
function logKioskoError($e)
{
    if (DEBUG && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        var_dump($e);
    $salida = "";
    $trace = $e->getTrace();
    foreach ($trace as $err) {
        $clase = $err['class'];
        $funcion = $err['function'];
        $salida .= $err['file'] . ';' . $err['line'] . '-' . $clase . $err['type'] . $funcion . "\n";
    }
    return gestionLog($e->getCode(), $e->getMessage() . "\n" . $salida, $e->getFile(), $e->getLine());
}

function gestionLog($errno, $errstr, $errfile, $errline)
{
    var_dump("En errores");
    echo ("<br/>".$errno);
    echo ("<br/>".$errstr);
    echo ("<br/>".$errfile);
    echo ("<br/>".$errline);
    
    defined('LOG_TRACE') or define('LOG_TRACE', 1);
    if (LOG_TRACE == 0) {
        if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
            if (! is_dir(LOG_RUTA))
                mkdir(LOG_RUTA, 0755, true);

            if (! is_dir(LOG_NOMBRE_FICHERO)) {
                $usuario = get_current_user();
                $conSudo = $usuario="pi"?'sudo ':'';
                //exec($conSudo .' touch ' . LOG_NOMBRE_FICHERO);
                //exec($conSudo . ' chown ' . $usuario . ':' . $usuario . ' ' . LOG_NOMBRE_FICHERO);
            }
        }
    }
    if (DEBUG && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        var_dump("Error:" . $errno . "\n" . $errstr . "\n" . $errfile . ',' . $errline);
    }
    if (! (error_reporting() & $errno)) {

        // through to the standard PHP error handler
        return false;
    }

    $errstr = limpiaMensaje($errstr);

    $filename = basename($errfile);
    $fechaHora = date("y-m-d H:i:s "); // . gettimeofday()["usec"];
    $cabeceraMensaje = gethostname() . "(" . getmypid() . ") " . $fechaHora;
    $controlMensaje = "$cabeceraMensaje $filename :$errline >>[$errno] $errstr\r\n";
    $controlMensajeMio = "$cabeceraMensaje $filename>>$errstr\r\n";

    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
        case E_STRICT:
            grabaLog("ST-" . $controlMensaje);
            break;

        case E_WARNING:
        case E_USER_WARNING:
            grabaLog("WR-" . $controlMensaje);
            break;

        case E_ERROR:
        case E_USER_ERROR:
        case E_RECOVERABLE_ERROR:
            grabaLog("FA-" . $controlMensaje);
            exit(1);
            break;

        default:
            grabaLog("IN-" . $controlMensajeMio);
            break;
    }

    // delete any files older than 30 days
    $files = glob(LOG_RUTA . "*");
    $now = time();

    foreach ($files as $file)
        if (is_file($file))
            if ($now - filemtime($file) >= 60 * 60 * 24 * LOG_DIAS_PERMANENCIA)
                unlink($file);
}
return true;

function fatal_handler() {
    $errfile = "unknown file";
    $errstr  = "shutdown";
    $errno   = E_CORE_ERROR;
    $errline = 0;
    
    $error = error_get_last();
    
    if( $error !== NULL) {
        $errno   = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr  = $error["message"];
        
        $pos = 
        //error_mail(format_error( $errno, $errstr, $errfile, $errline));
        print_r("<h1>Error 500</h1>");
        print_r("<br><b>Error:</b>" . $errno);
        print_r("<br><b>Mensaje:</b>" . $errstr);
        print_r( "<br><b>En </b>" . $errfile .',' . $errline);
        
    }
}
function process_error_backtrace($errno, $errstr, $errfile, $errline, $errcontext) {
    if(!(error_reporting() & $errno))
        return;
        switch($errno) {
            case E_WARNING      :
            case E_USER_WARNING :
            case E_STRICT       :
            case E_NOTICE       :
            case E_USER_NOTICE  :
                $type = 'warning';
                $fatal = false;
                break;
            default             :
                $type = 'fatal error';
                $fatal = true;
                break;
        }
        $trace = array_reverse(debug_backtrace());
        array_pop($trace);
        if(php_sapi_name() == 'cli') {
            echo 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
            foreach($trace as $item)
                echo '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()' . "\n";
        } else {
            echo '<p class="error_backtrace">' . "\n";
            echo '  Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
            echo '  <ol>' . "\n";
            foreach($trace as $item)
                echo '    <li>' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()</li>' . "\n";
                echo '  </ol>' . "\n";
                echo '</p>' . "\n";
        }
        if(ini_get('log_errors')) {
            $items = array();
            foreach($trace as $item)
                $items[] = (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()';
                $message = 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ': ' . join(' | ', $items);
                error_log($message);
        }
        if($fatal)
            exit(1);
}

set_error_handler('process_error_backtrace');
?>

// Don't execute PHP internal error handler
function setLog($mensaje)
{
    error_log("Mensaje" . $mensaje, 3, LOG_NOMBRE_FICHERO);
}

function getLog()
{
    $content = @file_get_contents(LOG_NOMBRE_FICHERO);
    return $content;
}

function grabaLog($mensaje)
{
    if (LOG_TRACE == 0) {
        file_put_contents(LOG_NOMBRE_FICHERO, $mensaje, FILE_APPEND | LOCK_EX);
    }
    if (LOG_TRACE == 1) {
        echo $mensaje;
    }
}

function debug_backtrace_string()
{
    $stack = '';
    $i = 1;
    $trace = debug_backtrace();
    unset($trace[0]); // Remove call to this function from stack trace
    foreach ($trace as $node) {
        $stack .= "#$i " . debug_node($node);
        $i ++;
    }
    return $stack;
}

function debug_node($node)
{
    $salida .= $node['file'] . "(" . $node['line'] . "): ";
    if (isset($node['class'])) {
        $salida .= $node['class'] . "->";
    }
    $salida .= $node['function'] . "()" . PHP_EOL;
    return $salida;
}

function limpiaMensaje($errstr)
{
     $valor=[SSH_PASSWORD,SSH_USUARIO,"SSHPASS","sshpass","/home/pi/.ssh/id_rsa","ssh-copy-id","autossh","http","kiosko","gestionproyectos","datos","/var/www/html/",
        "pi:pi","root:root","pi:root",SSH_HOST_DESTINO
    ];
    $cambio=["pass","user","ENVIAPAS",'enviapas','fic_rsa','enviakey','conecc','ktrf','dominio','ubica','dwk','usp:usp','usr:usr','usp:usr','IPWCONTROL'];
    $errstr =  preg_replace('/[\x00-\x1F|\x7F-\xFF]/u', " ", $errstr);     //Dejar solo caracteres imprimibles
    $errstr = str_replace($valor,$cambio, $errstr); 
     
    return $errstr;
    
}
function reconstruyeMensaje($errstr)
{
    $valor=[SSH_PASSWORD,SSH_USUARIO,"SSHPASS","sshpass","/home/pi/.ssh/id_rsa","ssh-copy-id","autossh","http","kiosko","gestionproyectos","datos","/var/www/html/",
        "pi:pi","root:root","pi:root",SSH_HOST_DESTINO
    ];
    $cambio=["pass","user","ENVIAPAS",'enviapas','fic_rsa','enviakey','conecc','ktrf','dominio','ubica','dwk','usp:usp','usr:usr','usp:usr','IPWCONTROL'];
    //$errstr =  preg_replace('/[\x00-\x1F|\x7F-\xFF]/u', " ", $errstr);     //Dejar solo caracteres imprimibles
    $errstr = str_replace($cambio,$valor, $errstr);
    
    return $errstr;
}