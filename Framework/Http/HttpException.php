<?php

namespace Framework\Http;

use Exception;
use Throwable;

/**
 * Custom exception class for handling HTTP-related errors.
 *
 * This class extends PHP's built-in `Exception` to provide more informative error handling with standard HTTP status
 * codes and messages. It's designed for use in web applications to simplify the management of HTTP errors.
 */
class HttpException extends Exception
{
    /**
     * Maps HTTP status codes to their standard reason phrases.
     *
     * This list includes commonly used HTTP status codes and some less common ones, such as WebDAV and specific
     * RFC codes, to support a wide range of HTTP responses.
     *  Grabbed from https://gist.github.com/henriquemoody/6580488
     *
     * @var array<int, string> Associative array of status codes and messages
     */
    private static array $statusCodeMessages = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing', // WebDAV; RFC 2518
        103 => 'Early Hints', // RFC 8297
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information', // since HTTP/1.1
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content', // RFC 7233
        207 => 'Multi-Status', // WebDAV; RFC 4918
        208 => 'Already Reported', // WebDAV; RFC 5842
        226 => 'IM Used', // RFC 3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // Previously "Moved temporarily"
        303 => 'See Other', // since HTTP/1.1
        304 => 'Not Modified', // RFC 7232
        305 => 'Use Proxy', // since HTTP/1.1
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect', // since HTTP/1.1
        308 => 'Permanent Redirect', // RFC 7538
        400 => 'Bad Request',
        401 => 'Unauthorized', // RFC 7235
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required', // RFC 7235
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed', // RFC 7232
        413 => 'Payload Too Large', // RFC 7231
        414 => 'URI Too Long', // RFC 7231
        415 => 'Unsupported Media Type', // RFC 7231
        416 => 'Range Not Satisfiable', // RFC 7233
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot', // RFC 2324, RFC 7168
        421 => 'Misdirected Request', // RFC 7540
        422 => 'Unprocessable Entity', // WebDAV; RFC 4918
        423 => 'Locked', // WebDAV; RFC 4918
        424 => 'Failed Dependency', // WebDAV; RFC 4918
        425 => 'Too Early', // RFC 8470
        426 => 'Upgrade Required',
        428 => 'Precondition Required', // RFC 6585
        429 => 'Too Many Requests', // RFC 6585
        431 => 'Request Header Fields Too Large', // RFC 6585
        451 => 'Unavailable For Legal Reasons', // RFC 7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates', // RFC 2295
        507 => 'Insufficient Storage', // WebDAV; RFC 4918
        508 => 'Loop Detected', // WebDAV; RFC 5842
        510 => 'Not Extended', // RFC 2774
        511 => 'Network Authentication Required', // RFC 6585
    ];

    /**
     * Constructs a new HttpException.
     *
     * @param int $statusCode The HTTP status code associated with the exception
     * @param null $message Custom error message (if not provided, a standard message is used)
     * @param Throwable|null $previous Optional previous exception for exception chaining
     */
    public function __construct(int $statusCode, $message = null, Throwable $previous = null)
    {
        parent::__construct(
            $message ? $message : self::$statusCodeMessages[$statusCode],
            $statusCode,
            $previous
        );
    }

    /**
     * Creates an HttpException from an existing throwable.
     *
     * This is useful for converting generic exceptions into standardized HTTP errors, often with a default status
     * code of 500 (Internal Server Error).
     *
     * @param Throwable $exception The original exception to be wrapped
     * @param int $statusCode The HTTP status code to use (default: 500)
     * @return HttpException The wrapped exception
     */
    public static function from(Throwable $exception, int $statusCode = 500): HttpException
    {
        return new HttpException($statusCode, null, $exception);
    }
}
