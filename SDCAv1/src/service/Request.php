<?php
namespace service;

class Request
{
    //-------------------------------------http://www.contaaut.es:8000/api_v0.0/prueba/uno?a=10&b=20
    private $host;                          //www.eclipse:8000
    private $domain;                        //contaaut.es
    private $subdomain;                     //www
    private $extension;                     //es
    private $port;                          //8000
    private $path;                          ///api_v0.0/prueba/uno$a=10&b=20
    private $params;                        // { ["a"]=> string(2) "10" ["b"]=> string(2) "20" }
    private $method;                        //GET
    private $proto;                         //http
    private $ruta;                          //[0]=> string(0) "" [1]=> string(8) "api_v0.0" [2]=> string(6) "prueba" [3]=> string(3) "uno" 
    private $urlParse;                       //url dividida
    private $user;                          //""
    private $password;                      //""
    
    
   

    public function __construct()
    {
        if (!isset($_SERVER['HTTP_HOST'])) exit;  //Siempre debe existir
        $this->host=$_SERVER['HTTP_HOST'];
        $this->urlParse = $this->parseUrl($this->host);
        
        $this->domain = $this->urlParse['domain'];
        $this->subdomain =  $this->urlParse['subdomain'];
        $this->extension = $this->urlParse['extension'];
        $this->port = $this->urlParse['port'];

        $this->host=$_SERVER['HTTP_HOST'];
        $this->path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->ruta = explode('/', $this->path);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $ssl   = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
        $proto = strtolower($_SERVER['SERVER_PROTOCOL']);
        $this->proto = substr($proto, 0, strpos($proto, '/')) . ($ssl ? 's' : '' );
        $this->params = array_merge($_POST, $_GET);  
        
        $this->user = isset($this->urlParse['login']) ? $this->urlParse['login'] : $_SERVER['PHP_AUTH_USER'];
        $this->password = isset($this->urlParse['pass']) ? $this->urlParse['pass'] : $_SERVER['PHP_AUTH_PW'] ;
        
        
    }
    public function getAttribute(string $name)
    {
        return $_REQUEST[$name] ?? null;
    }
    public function setAttribute(string $name,$valor)
    {
        $_REQUEST[$name] = $valor;
    }
    
    public function setParam(string $nombre, $valor)
    {
        $this->params[$nombre]=$valor;
    }
    public function getUrl(): string
    {
        return  $this->proto . '://' .$this->host . "/";         $this->path;
    }

    public function gethost(): string
    {
        return $this->host;
    }
    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }
    
    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }
    
    /**
     * @return array
     */
    public function getRuta()
    {
        return $this->ruta;
    }
    
    /**
     * @return mixed
     */
    public function getUrlParse()
    {
        return $this->urlParse;
    }
    
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    

    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }
    
       
    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $name)
    {
        return $this->params[$name] ?? null;
    }

    public function getParamDefault(string $name, string $default)
    {
        return $this->params[$name] ?? $default;
    }
    
    public function getParamSpace(string $name):string
    {
        return $this->params[$name] ?? "";
    }

    public function getTime(string $name)
    {
        return $this->params[$name] ?? 0;
    }
    public function getParamNumer(string $name):int
    {
        return (int)($this->params[$name] ?? 0);
    }
    public function getParamDouble(string $name):float
    {
        return floatval(($this->params[$name] ?? 0));
        //return $this->params[$name] ?? "";
    }

    public function hasParam(string $name): bool
    {
        return isset($this->params[$name]);
    }
    
    /**
     * @return mixed
     */
    public function getdomain()
    {
        return $this->domain;
    }
    
    function parseUrl($url) {
        //$url = '@' . $url;
        $r='';
        $r  = "^(?:(?P<scheme>\w+)://)?";
        $r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
        $r .= "(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?" . "(?P<domain>\w+\.(?P<extension>\w+)))";
        $r .= "(?::(?P<port>\d+))?";
        $r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
        $r .= "(?:\?(?P<arg>[\w=&]+))?";
        $r .= "(?:#(?P<anchor>\w+))?";
        $r = "!$r!";                                                // Delimiters
        //$url = 'me:you@sub.site.org:29000/pear/validate.html?happy=me&sad=you#url';
        preg_match ( $r, $url, $out );
        
        return $out;
    }
    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getProto()
    {
        return $this->proto;
    }

    

}
?>
