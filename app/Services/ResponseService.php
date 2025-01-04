<?php

namespace App\Services;

use Countable;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ResponseService {
    private const DEFAULT_ERROR_CODE = 500;

    private const RESPONSE_COLLUMN_SUCCESS = "success";
    private const RESPONSE_COLLUMN_MESSAGE = "message";
    private const RESPONSE_COLLUMN_DATA = "payload";
    private const RESPONSE_COLLUMN_COUNT = "count";
    private const RESPONSE_COLLUMN_TRACE = "trace";
    private const RESPONSE_COLLUMN_ERRORS = "errors";


    /**
     * Return a JSON response with the data passed
     *
     * @param array|object $data data to be returned
     * @param string $message    message to be displayed
     * @param integer $code      HTTP status code
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-06-08
     */
    public static function success(array|object $data = [], int $count = null, string $message = "Request successfully", int $code = 200): Response|ResponseFactory {

        return response(
            [
                self::RESPONSE_COLLUMN_SUCCESS => true,
                self::RESPONSE_COLLUMN_MESSAGE => __($message),
                self::RESPONSE_COLLUMN_DATA => $data,
                self::RESPONSE_COLLUMN_COUNT => $count ?? (is_countable($data) ? count($data) : (empty($data) ? 0 : 1))
            ],
            $code
        );
    }

    /**
     * Return a JSON response with the error message passed
     *
     * @param \Exception|\Illuminate\Validation\ValidationException $e
     * @param integer|null $code
     *
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-06-08
     */
    public static function error(Exception|ValidationException $e, int|string $code = NULL): Response|ResponseFactory {
        if ($e->getCode() != 0 && empty($code) && self::isValidHttpCode($e->getCode())) {
            $code = $e->getCode();
        }

        $code = self::isValidHttpCode($code) ? $code : self::DEFAULT_ERROR_CODE;

        return response(
            [
                self::RESPONSE_COLLUMN_SUCCESS => false,
                self::RESPONSE_COLLUMN_MESSAGE => __($e->getMessage()),
                self::RESPONSE_COLLUMN_ERRORS => $e instanceof ValidationException ? collect($e->errors())->toArray() : null,
                self::RESPONSE_COLLUMN_TRACE => $e->getTrace(),
                self::RESPONSE_COLLUMN_DATA => null,
                self::RESPONSE_COLLUMN_COUNT => 0,
            ],
            $code ?: self::DEFAULT_ERROR_CODE
        );
    }

    /**
     * Check if the HTTP code is valid
     *
     * @param integer $code
     *
     * @return boolean
     *
     * @author Kauan Morinel Calheiro <kauan.calheiro@universo.univates.br>
     *
     * @date 2024-06-12
     */
    private static function isValidHttpCode(int|string $code = null): bool {
        if (empty($code)) {
            return false;
        }

        if (is_string($code)) {
            return false;
        }

        return $code >= 100 && $code <= 599;
    }
}