<?php namespace Aliyun\OSS\Test;

use Aliyun\OSS\Core\Exception;
use Aliyun\OSS\Http\ResponseCore;
use Aliyun\OSS\Result\AclResult;

class AclResultTest extends \PHPUnit_Framework_TestCase
{

    private $validXml = <<<BBBB
<?xml version="1.0" ?>
<AccessControlPolicy>
    <Owner>
        <ID>00220120222</ID>
        <DisplayName>user_example</DisplayName>
    </Owner>
    <AccessControlList>
        <Grant>public-read</Grant>
    </AccessControlList>
</AccessControlPolicy>
BBBB;

    private $invalidXml = <<<BBBB
<?xml version="1.0" ?>
<AccessControlPolicy>
</AccessControlPolicy>
BBBB;


    public function testParseValidXml()
    {
        $response = new ResponseCore(array(), $this->validXml, 200);
        $result   = new AclResult($response);
        $this->assertEquals("public-read", $result->getData());
    }


    public function testParseNullXml()
    {
        $response = new ResponseCore(array(), "", 200);
        try {
            new AclResult($response);
            $this->assertTrue(false);
        } catch (Exception $e) {
            $this->assertEquals('body is null', $e->getMessage());
        }
    }


    public function testParseInvalidXml()
    {
        $response = new ResponseCore(array(), $this->invalidXml, 200);
        try {
            new AclResult($response);
            $this->assertFalse(true);
        } catch (Exception $e) {
            $this->assertEquals("xml format exception", $e->getMessage());
        }
    }
}
