<?php
namespace App\controller;

use service\Request;
use App\dao\DaoComunidades;
use App\dao\DaoProvincia;
use App\dao\DaoPoblacion;
use App\dao\DaoCodigoPostal;
use Psr\Container\ContainerInterface;
use Slim\Http\Response;
use Slim\Http\ServerRequest;
use Slim\Exception\HttpNotFoundException;


final class ControllerAPI
{

    private $cDaoCCAA;

    private $cDaoProv;

    private $cDaoPobla;

    private $cDaoCPos;

    private $request;
    
    protected $container;

    function __construct(DaoComunidades $cDaoCCAA, DaoProvincia $cDaoProv, DaoPoblacion $cDaoPobla, 
        DaoCodigoPostal $cDaoCPos, ContainerInterface $container)
    {
        $this->cDaoCCAA = $cDaoCCAA;
        $this->cDaoProv = $cDaoProv;
        $this->cDaoCPos = $cDaoCPos;
        $this->cDaoPobla = $cDaoPobla;
        $this->container = $container;
    }

 
    

    /**
     * Listado
     */
    public function listadoCCAA(ServerRequest $request, Response $response): Response
    {
        $result = $this->cDaoCCAA->listJson();
       // var_dump($result);
       // return $result;
        return $response->write($result)->withStatus(200);
    }

    public function listadoProvin(ServerRequest $request, Response $response): Response
    {
        $result = $this->cDaoProv->listJson();
        return $response->write($result)->withStatus(200);
    }

    public function listadoPobla(ServerRequest $request, Response $response): Response
    {
        $result =  $this->cDaoPobla->listJson();
        return $response->write($result)->withStatus(200);
    }

    public function listadoCodPos(ServerRequest $request, Response $response): Response
    {
        $result= $this->cDaoCPos->listJson();
        return $response->write($result)->withStatus(200);
    }

    public function listadoCCAAW(ServerRequest $request, Response $response): Response
    {
        $request->getParamNumer('id');
        $result= $this->cDaoCCAA->listJson();
        return $response->write($result)->withStatus(200);
    }

    public function listadoProvinW(ServerRequest $request, Response $response): Response
    {
        $comua = $request->getParamNumer('ccaa');
        $w = cppro_codca . "=" . $comua;
        $result= $this->cDaoProv->listConWhereJson($w);
        return $response->write($result)->withStatus(200);
    }

    public function listadoPoblaW(ServerRequest $request, Response $response): Response
    {
        $provin = $request->getParamNumer('provin');
        $w = cppro_id . "=" . $provin;
        $result= $this->cDaoPobla->listConWhereJson($w);
        return $response->write($result)->withStatus(200);
    }

    public function listadoCodPosW(ServerRequest $request, Response $response): Response
    {
        $pobla = $request->getParamNumer('pobla');
        $w = cppob_id . "=" . $pobla;
        $result= $this->cDaoCPos->listConWhereJson($w);
        return $response->write($result)->withStatus(200);
    }
}

