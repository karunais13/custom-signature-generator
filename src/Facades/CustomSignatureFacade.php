<?php
namespace Karu\CustomSignature\Facades;

use Illuminate\Support\Facades\Facade;

class CustomSignatureFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return '\Karu\CustomSignature\Helpers\CustomSignatureHelper';
    }
}
