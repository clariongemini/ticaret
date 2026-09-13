<?php

namespace App\SharedKernel\Context;

class TenantContext
{
    private static ?string $tenantId = null;
    private static bool $isSystemContext = false;

    public static function executeForTenant(string $tenantId, callable $callback)
    {
        $previousTenantId = self::$tenantId;
        $previousSystemState = self::$isSystemContext;
        
        self::setTenantId($tenantId);
        self::$isSystemContext = false; // Strictly enforce non-system context for tenant code
        
        try {
            return $callback();
        } finally {
            self::$tenantId = $previousTenantId;
            self::$isSystemContext = $previousSystemState;
            
            if (self::$tenantId === null && self::$isSystemContext === false) {
                self::clear();
            }
        }
    }

    public static function executeAsSystem(callable $callback)
    {
        $previousState = self::$isSystemContext;
        self::$isSystemContext = true;
        
        try {
            return $callback();
        } finally {
            self::$isSystemContext = $previousState;
        }
    }

    public static function isSystemContext(): bool
    {
        return self::$isSystemContext;
    }

    public static function setTenantId(string $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    public static function getTenantId(): ?string
    {
        return self::$tenantId;
    }

    public static function clear(): void
    {
        self::$tenantId = null;
        self::$isSystemContext = false;
    }
}
