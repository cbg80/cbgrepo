<?php
namespace tests\webapp_controllers;

use PHPUnit\Framework\TestCase;

class PostMakerControllerTest extends TestCase
{

    public function testMakePost()
    {
        //$this->assertEquals('ImageThread', ucfirst('image' . 'Thread'));
        $ch = curl_init();
        $options = [
            CURLOPT_URL => 'http://localhost/webapp_controllers/front_controller.php',
            CURLOPT_CUSTOMREQUEST => 'POST',
            
            CURLOPT_POSTFIELDS => [
                'action' => 'doMakePost',
                'imgTitle' => 'testing',
                'imgFile' => new \CURLFile(__DIR__ . '/../localdir/VID-20150721-WA0000.mp4', 'video/mp4', 'test_name')
            ],
            
            CURLOPT_RETURNTRANSFER => TRUE
        ];
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, (int)$httpStatusCode);
    }
}