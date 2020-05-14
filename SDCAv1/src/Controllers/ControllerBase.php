<?php
namespace controller;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\service\Request;

class ControllerBase
{
    private $cDao;
    private $model;

    private $request;
    private $param;

    function __construct($cDao,$cModel)
    {
        $this->cDao = $cDao;      
        $this->model = $cModel;
        $this->request = new Request();
    }

    public function init($request,$param)
    {
        $this->request = $request;
        $this->param = $param;

        switch ($this->request->getMethod()){
            case 'GET':
                $retorno = $this->procesoGet($request,$param);
                break;
            case 'PUT':
                echo "Paso";
                $retorno = $this->procesoPut($request,$param);
                break;
            case 'POST':
                $retorno = $this->procesoPost($request,$param);
                break;
            case 'DELETE':
                $retorno = $this->procesoDelete($request,$param);
                break;
        }
        return $retorno;
    }

    public function procesoGet($request,$param){
        if (empty($param[0])) {
            return $this->listAll();
        } else {
            if ($param[0] == 'list') {
                return $this->listAll();
            } else {
                if (is_numeric($param[0])){
                    //echo 'Num------>'. $param[0];
                    return $this->listUno((int) $param[0]);
                } else {
                    //echo 'str------>'. $param[0];
                    return $this->listUno($param[0]);
                }
            }
        }   
    }
    /**
     * Listado
     * return String
     * carga $_REQUEST['salida'] con un array de objetos Cliente *
     */
    public function listAll()
    {
        $_REQUEST['salida'] = $this->convierteArray($this->cDao->listAll(), $this->model );   
        return "";
    }
    
    public function listUno($key){
        
        $_REQUEST['salida'] = $this->convierteArray($this->cDao->listPorId($key), $this->model );
        return "";
    }

    
  
    public function  procesoPut($request,$param)
    {
        if (empty(INPUT)) return;
        $objeto = $this->model->setFromArray(INPUT);
        $this->cDao->update($objeto);
        $_REQUEST['salida'] ='ok';
        return "";
    }

    public function procesoPost($request,$param)
    {
        $objeto = $this->model->setFromArray(INPUT);
        $this->cDao->inserta($objeto);
        $_REQUEST['salida'] =$objeto;
        return "";
    }

    public function procesoDelete($request,$param)
    {
        $objeto = $this->cDao->listPorId((int) $param[0]);
        $this->cDao->borra($objeto);
        $_REQUEST['salida'] ='ok';
        return "";
    }

   /**
    * Rutinas comunes
    */
    
    /**
     * Recibe un array de objetos y prepara arrapara JSON
     * Segun definiciones de modelo
     * 
    * @param  $lista
    * @param  $model
    * @return array
    */    
    public function convierteArray($lista, $model ){
        $resp = [];
        if (is_array($lista)) {
            foreach ($lista as $elem){
                array_push($resp,$elem->getInArray());
                
            }
        } else
            $resp = $lista->getInArray();
            return $resp;
    }
}

