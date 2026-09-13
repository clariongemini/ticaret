<?php

namespace App\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    /**
     * @var string
     */
    protected $errorCode;

    /**
     * @var int
     */
    protected $statusCode;

    public function __construct(string $message = "", string $errorCode = "ERR_INTERNAL", int $statusCode = 500, ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => $this->errorCode,
                'message' => $this->getMessage(),
                'correlation_id' => $request->header('X-Correlation-ID'),
            ]
        ], $this->statusCode);
    }
}
