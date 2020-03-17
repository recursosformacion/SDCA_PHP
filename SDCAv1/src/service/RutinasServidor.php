<?php
namespace service;

class RutinasServidor
{
    /**
     * $rootdir directorio a explorar
     * $root devolver con raiz(true) o solo el nombre (false)
     * $allData array de devolucion
     */
    public static function  scanDirectories($rootDir, $root = false, $allData = array())
    {
        // set filenames invisible if you want
        $invisibleFileNames = array(
            ".",
            "..",
            ".htaccess",
            ".htpasswd"
        );
        // run through content of root directory
        $dirContent = scandir($rootDir);
        foreach ($dirContent as $key => $content) {
            // filter all files not accessible
            $path = $rootDir . '/' . $content;
            if (! in_array($content, $invisibleFileNames)) {
                // if content is file & readable, add to array
                if (is_file($path) && is_readable($path)) {
                    // save file name with path
                    if ($root) {
                        $allData[] = $path;
                    } else {
                        $allData[] = $content;
                    }
                    // if content is a directory and readable, add path and name
                } elseif (is_dir($path) && is_readable($path)) {
                    // recursive callback to open new directory
                    $allData = scanDirectories($path, $root, $allData);
                }
            }
        }
        return $allData;
    }
    /**
     * Envio de correos
     * @param string $to
     * @param string $asunto
     * @param string $mensaje
     */
    public static function enviarMail(string $to,string $asunto,string $mensaje="Sin Mensaje") :bool
    {
        $subject = $asunto;
        $headers = "MIME-Version: 1.0" . "\r\n";
        //        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From:' . CORREO_ORIGEN . "\r\n";
        //        $headers .= 'Cc: myboss@example.com' . "\r\n";
        //        $headers .= 'Reply-To: kiosko@gestionproyectos.com' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        
        $success=mail($to, $subject, $mensaje, $headers);
        if (!$success) {
            logKiosko(error_get_last()['message'],'enviarMail');
            return false;
        }
        return true;
    }
    public static function enviarMailcAtt(string $to,string $de, string $deName,string $subject, $file,$mensaje="Sin Mensaje"){
        
        //sender
        $from = ($de=="")?CORREO_ORIGEN:$de;
        $fromName = ($deName=="")?'Interno':$deName;
        
        //attachment file path
       // $file = "codexworld.pdf";
        
        //email body content
        $htmlContent = $mensaje;
        
        //header for sender info
        $headers = "From: $fromName"." <".$from.">";
        
        //boundary
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        
        //headers for attachment
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";
        
        //multipart boundary
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";
        
        //preparing attachment
        if(!empty($file) > 0){
            if(is_file($file)){
                $message .= "--{$mime_boundary}\n";
                $fp =    @fopen($file,"rb");
                $data =  @fread($fp,filesize($file));
                
                @fclose($fp);
                $data = chunk_split(base64_encode($data));
                $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .
                    "Content-Description: ".basename($file)."\n" .
                    "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .
                    "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            }
        }
        $message .= "--{$mime_boundary}--";
        $returnpath = "-f" . $from;
        
        //send email
        $mail = @mail($to, $subject, $message, $headers, $returnpath); 
    }
    
    // se ha de revisar
    public static function enviarMailRemoto($to, $subject, $body, $headers){
        $smtp = stream_socket_client('tcp://smtp.gestionproyectos.com:25', $eno, $estr, 30);
        
        $B = 8192;
        $c = "\r\n";
        $s = 'kiosko@gestionproyectos.com';
        
        fwrite($smtp, 'helo ' . $_ENV['HOSTNAME'] . $c);
        $junk = fgets($smtp, $B);
        
        // Envelope
        fwrite($smtp, 'mail from: ' . $s . $c);
        $junk = fgets($smtp, $B);
        fwrite($smtp, 'rcpt to: ' . $to . $c);
        $junk = fgets($smtp, $B);
        fwrite($smtp, 'data' . $c);
        $junk = fgets($smtp, $B);
        
        // Header
        fwrite($smtp, 'To: ' . $to . $c);
        if(strlen($subject)) fwrite($smtp, 'Subject: ' . $subject . $c);
        if(strlen($headers)) fwrite($smtp, $headers); // Must be \r\n (delimited)
        fwrite($smtp, $headers . $c);
        
        // Body
        if(strlen($body)) fwrite($smtp, $body . $c);
        fwrite($smtp, $c . '.' . $c);
        $junk = fgets($smtp, $B);
        
        // Close
        fwrite($smtp, 'quit' . $c);
        $junk = fgets($smtp, $B);
        fclose($smtp);
        
    }
    public static function beautify_filename($filename) {
        // reduce consecutive characters
        $filename = preg_replace(array(
            // "file   name.zip" becomes "file-name.zip"
            '/ +/',
            // "file___name.zip" becomes "file-name.zip"
            '/_+/',
            // "file---name.zip" becomes "file-name.zip"
            '/-+/'
        ), '-', $filename);
        $filename = preg_replace(array(
            // "file--.--.-.--name.zip" becomes "file.name.zip"
            '/-*\.-*/',
            // "file...name..zip" becomes "file.name.zip"
            '/\.{2,}/'
        ), '.', $filename);
        // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
        $filename = mb_strtolower($filename, mb_detect_encoding($filename));
        // ".file-name.-" becomes "file-name"
        $filename = trim($filename, '.-');
        return $filename;
    }
    
    public static function formatTiempo(String $tiempo,int $salida=0){
        if ($salida==0){
            $split = explode(':', $tiempo);
            if (count($split)<3) $split[2]=0;
            foreach ($split as $i => $v) $split[$i] = str_pad($v, 2, '0', STR_PAD_LEFT);
            $final = implode(':', $split);
            //var_dump($final);
            return $final;
        }
    }
    
    /**
     * Comprobar si un puerto esta abierto
     * 
     */
    function checkPort($ip, $portt) {
        $fp = @fsockopen($ip, $portt, $errno, $errstr, 0.1);
        if (!$fp) {
            return false;
        } else {
            fclose($fp);
            return true;
        }
    }
    function comprobarPuerto($port) {
        $serverConn = @stream_socket_client("tcp://127.0.0.1:{$port}", $errno, $errstr);
        if ($errstr != '') {
            return false;
        }
        fclose($serverConn);
        return true;
    }
    
}

