<?php

namespace App\Exceptions;

class TenantIsolationException extends ApiException
{
    public function __construct(string $message = "Tenant context is missing or invalid. Access denied.", int $code = 403)
    {
        parent::__construct($message, "ERR_TENANT_ISOLATION", $code);
    }
}
