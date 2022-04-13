<?php

namespace BringupMinabe\ShiftSdkPhp\Test;

use BringupMinabe\ShiftSdkPhp\ShiftSdkPhp;
use PHPUnit\Framework\TestCase;

class ShiftSdkPhpTest extends TestCase {

    /**
     * Test __setApiBaseUrl
     *
     * @return void
     */
    public function testSetApiBaseUrl()
    {
        $ShiftSdkPhp = new ShiftSdkPhp('http://localhost');
        $tests = [
            'http://localhost',
            'http://localhost/',
            'http://localhost ',
            'http://localhost/ ',
            ' http://localhost/ ',
        ];
        foreach ($tests as $value) {
            $this->assertEquals(
                'http://localhost',
                $ShiftSdkPhp->__setApiBaseUrl($value)
            );
        }
    }

    /**
     * Test __setEndPoint
     *
     * @return void
     */
    public function testSetEndPoint()
    {
        $ShiftSdkPhp = new ShiftSdkPhp('http://localhost');
        $tests = [
            'endPoint',
            'endPoint/',
            '/endPoint ',
            'endPoint/ ',
            ' /endPoint/ ',
        ];
        foreach ($tests as $value) {
            $this->assertEquals(
                'endPoint',
                $ShiftSdkPhp->__setEndPoint($value)
            );
        }
    }

    /**
     * Test getApiKey
     *
     * @return void
     */
    public function testGetApiKey()
    {
        $ShiftSdkPhp = new ShiftSdkPhp('http://localhost');
        $tests = [
            'key',
            ' key',
            'key ',
        ];
        foreach ($tests as $value) {
            $this->assertEquals(
                'key',
                $ShiftSdkPhp->__setApiKey($value)
            );
        }
    }

    /**
     * Test getApiSecret
     *
     * @return void
     */
    public function testGetApiSecret()
    {
        $ShiftSdkPhp = new ShiftSdkPhp('http://localhost');
        $tests = [
            'secret',
            ' secret',
            'secret ',
        ];
        foreach ($tests as $value) {
            $this->assertEquals(
                'secret',
                $ShiftSdkPhp->__setApiSecret($value)
            );
        }
    }

}
