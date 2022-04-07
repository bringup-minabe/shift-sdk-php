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
        $ShiftSdkPhp = new ShiftSdkPhp(
            'http://example.com',
            'key',
            'secret'
        );
        $tests = [
            'http://example.com',
            'http://example.com/',
            'http://example.com ',
            'http://example.com/ ',
            ' http://example.com/ ',
        ];
        foreach ($tests as $value) {
            $this->assertEquals(
                'http://example.com',
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
        $ShiftSdkPhp = new ShiftSdkPhp(
            'http://example.com',
            'key',
            'secret'
        );
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
        $tests = [
            'key',
            ' key',
            'key ',
        ];
        foreach ($tests as $value) {
            $ShiftSdkPhp = new ShiftSdkPhp(
                'http://example.com',
                $value,
                'secret'
            );
            $this->assertEquals(
                'key',
                $ShiftSdkPhp->getApiKey()
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
        $tests = [
            'secret',
            ' secret',
            'secret ',
        ];
        foreach ($tests as $value) {
            $ShiftSdkPhp = new ShiftSdkPhp(
                'http://example.com',
                'key',
                $value
            );
            $this->assertEquals(
                'secret',
                $ShiftSdkPhp->getApiSecret()
            );
        }
    }

}
