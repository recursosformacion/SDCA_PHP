<?php
use phpDocumentor\Reflection\Types\Array_;
use App\modelos\Co_usuarios;

require_once __DIR__ . '/../../vendor/autoload.php';


/**
 * Co_usuarios test case.
 */
class Co_usuariosTest extends TestCase 
{
// Datos para usuario 1
    const ID = 999999999;
    const NOMBRE = "Miguel Garcia";
    const MNEMONICO = "migarcia";
    const MAIL = "migarcia@dopc.com";
    const PASSWORD = "1234567";
//Datos para usuario 2    
    const ID2 = 9999999999;
    const NOMBRE2 = "Miguel Garcia2";
    const MNEMONICO2 = "migarcia2";
    const MAIL2 = "migarcia@dopc.com2";
    const PASSWORD2 = "12345678";
    
    const TABLA = "co_usuarios";
    const PK = "id_usuario";
    
    
    //Controles desde array
    private $array1 = array();
    const SELEC = array (
        0 => "id_usuario",
        1 => "cou_nombre"
    );
    
    /**
     *
     * @var Co_usuarios
     */
    private $co_usuarios;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->co_usuarios = new Co_usuarios(self::ID,self::NOMBRE,self::MNEMONICO,self::MAIL,self::PASSWORD);
        $this->array1 = [
            "id_usuario"=> self::ID,
            "cou_nombre"=> self::NOMBRE,
            "cou_mnemonico"=> self::MNEMONICO,
            "cou_mail"=> self::MAIL,
            "cou_password"=> self::PASSWORD
        ];
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated Co_usuariosTest::tearDown()
        $this->co_usuarios = null;

        parent::tearDown();
    }


    /**
     * Tests Co_usuarios->getid()
     */
    public function testGetid()
    {   
        $this->assertEquals(self::ID,$this->co_usuarios->getid());      
    }

    /**
     * Tests Co_usuarios->getId_usuario()
     */
    public function testGetId_usuario()
    {
        $this->assertSame(self::ID,$this->co_usuarios->getId_usuario());    
        
    }

    /**
     * Tests Co_usuarios->getCou_nombre()
     */
    public function testGetCou_nombre()
    {
        $this->assertSame(self::NOMBRE,$this->co_usuarios->getCou_nombre());
    }

    /**
     * Tests Co_usuarios->getCou_mnemonico()
     */
    public function testGetCou_mnemonico()
    {
        $this->assertSame(self::MNEMONICO,$this->co_usuarios->getCou_mnemonico());
    }

    /**
     * Tests Co_usuarios->getCou_mail()
     */
    public function testGetCou_mail()
    {
        $this->assertSame(self::MAIL,$this->co_usuarios->getCou_mail());

    }

    /**
     * Tests Co_usuarios->getCou_password()
     */
    public function testGetCou_password()
    {
        $this->assertSame(self::PASSWORD,$this->co_usuarios->getCou_password());
    }

    /**
     * Tests Co_usuarios->setid()
     */
    public function testSetid()
    {
        $this->co_usuarios->setid(self::ID2);
        $this->assertEquals(self::ID2,$this->co_usuarios->getid());      
    }

    /**
     * Tests Co_usuarios->setId_usuario()
     */
    public function testSetId_usuario()
    {
        $this->co_usuarios->setId_usuario(self::ID2);
        $this->assertSame(self::ID2,$this->co_usuarios->getId_usuario());    
    }

    /**
     * Tests Co_usuarios->setCou_nombre()
     */
    public function testSetCou_nombre()
    {
        $this->co_usuarios->setCou_nombre(self::NOMBRE2);
         $this->assertSame(self::NOMBRE2,$this->co_usuarios->getCou_nombre());
    }

    /**
     * Tests Co_usuarios->setCou_mnemonico()
     */
    public function testSetCou_mnemonico()
    {
        $this->co_usuarios->setCou_mnemonico(self::MNEMONICO2);
        $this->assertSame(self::MNEMONICO2,$this->co_usuarios->getCou_mnemonico());
    }

    /**
     * Tests Co_usuarios->setCou_mail()
     */
    public function testSetCou_mail()
    {
        $this->co_usuarios->setCou_mail(self::MAIL2);
        $this->assertSame(self::MAIL2,$this->co_usuarios->getCou_mail());
    }

    /**
     * Tests Co_usuarios->setCou_password()
     */
    public function testSetCou_password()
    {
        $this->co_usuarios->setCou_password(self::PASSWORD2);
        $this->assertSame(self::PASSWORD2,$this->co_usuarios->getCou_password());
    }

    /**
     * Tests Co_usuarios->getInArray()
     */
    public function testGetInArray()
    {
        $this->assertSame($this->array1,$this->co_usuarios->getInArray());
    }

    /**
     * Tests Co_usuarios::setFromArray()
     */
    public function testSetFromArray()
    {
       
        $usuario = Co_usuarios::setFromArray($this->array1);
        $this->assertSame($this->array1,$usuario->getInArray());
    }

    /**
     * Tests Co_usuarios::getNombreId()
     */
    public function testGetNombreId()
    {      
        $this->assertSame(self::PK,Co_usuarios::getNombreId());
    }

    /**
     * Tests Co_usuarios::getNombreTabla()
     */
    public function testGetNombreTabla()
    {
        $this->assertSame(self::TABLA, Co_usuarios::getNombreTabla());
    }

    /**
     * Tests Co_usuarios->getSelect()
     */
    public function testGetSelect()
    {
        $this->assertSame(self::SELEC,$this->co_usuarios->getSelect());
    }
}

