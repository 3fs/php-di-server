<?php
namespace trifs\DIServer;

class Http
{
    /**
     * @var int
     */
    const CODE_CONTINUE = 100;

    /**
     * @var int
     */
    const CODE_SWITCHING = 101;

    /**
     * @var int
     */
    const CODE_PROCESSING = 102;

    /**
     * @var int
     */
    const CODE_OK = 200;

    /**
     * @var int
     */
    const CODE_CREATED = 201;

    /**
     * @var int
     */
    const CODE_ACCEPTED = 202;

    /**
     * @var int
     */
    const CODE_NON_AUTHORATIVE = 203;

    /**
     * @var int
     */
    const CODE_DELETED = 204;

    /**
     * @var int
     */
    const CODE_RESET_CONTENT = 205;

    /**
     * @var int
     */
    const CODE_PARTIAL_CONTENT = 206;

    /**
     * @var int
     */
    const CODE_MULTIPLE_CHOICES = 300;

    /**
     * @var int
     */
    const CODE_MOVED_PERMANENTLY = 301;

    /**
     * @var int
     */
    const CODE_FOUND = 302;

    /**
     * @var int
     */
    const CODE_SEE_OTHER = 303;

    /**
     * @var int
     */
    const CODE_NOT_MODIFIED = 304;

    /**
     * @var int
     */
    const CODE_USE_PROXY = 305;

    /**
     * @var int
     */
    const CODE_SWITCH_PROXY = 306;

    /**
     * @var int
     */
    const CODE_TEMPORARY_REDIRECT = 307;

    /**
     * @var int
     */
    const CODE_PERMANENT_REDIRECT = 308;

    /**
     * @var int
     */
    const CODE_BAD_REQUEST = 400;

    /**
     * @var int
     */
    const CODE_UNAUTHORIZED = 401;

    /**
     * @var int
     */
    const CODE_PAYMENT_REQUIRED = 402;

    /**
     * @var int
     */
    const CODE_FORBIDDEN = 403;

    /**
     * @var int
     */
    const CODE_NOT_FOUND = 404;

    /**
     * @var int
     */
    const CODE_METHOD_NOT_ALLOWED = 405;

    /**
     * @var int
     */
    const CODE_NOT_ACCEPTABLE = 406;

    /**
     * @var int
     */
    const CODE_PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * @var int
     */
    const CODE_REQUEST_TIMEOUT = 408;

    /**
     * @var int
     */
    const CODE_CONFLICT = 409;

    /**
     * @var int
     */
    const CODE_GONE = 410;

    /**
     * @var int
     */
    const CODE_LENGTH_REQUIRED = 411;

    /**
     * @var int
     */
    const CODE_PRECONDITION_FAILED = 412;

    /**
     * @var int
     */
    const CODE_REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * @var int
     */
    const CODE_REQUEST_URI_TOO_LONG = 414;

    /**
     * @var int
     */
    const CODE_UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * @var int
     */
    const CODE_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /**
     * @var int
     */
    const CODE_EXPECTATION_FAILED = 417;

    /**
     * @var int
     */
    const CODE_TEAPOT = 418;

    /**
     * @var int
     */
    const CODE_LOCKED = 423;

    /**
     * @var int
     */
    const CODE_UPGRADE_REQUIRED = 426;

    /**
     * @var int
     */
    const CODE_PRECONDITION_REQUIRED = 428;

    /**
     * @var int
     */
    const CODE_TOO_MANY_REQUESTS = 429;

    /**
     * @var int
     */
    const CODE_REQUEST_HEADERS_TOO_LARGE = 431;

    /**
     * @var int
     */
    const CODE_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    /**
     * @var int
     */
    const CODE_INTERNAL_ERROR = 500;

    /**
     * @var int
     */
    const CODE_NOT_IMPLEMENTED = 501;

    /**
     * @var int
     */
    const CODE_BAD_GATEWAY = 502;

    /**
     * @var int
     */
    const CODE_SERVICE_UNAVAILABLE = 503;

    /**
     * @var int
     */
    const CODE_GATEWAY_TIMEOUT = 504;

    /**
     * @var int
     */
    const CODE_HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * @var int
     */
    const CODE_VARIANT_ALSO_NEGOTIATES = 506;

    /**
     * @var int
     */
    const CODE_INSUFFICIENT_STORAGE = 507;

    /**
     * @var int
     */
    const CODE_LOOP_DETECTED = 508;

    /**
     * @var int
     */
    const CODE_BANDWITH_LIMIT_EXCEEDED = 509;

    /**
     * @var int
     */
    const CODE_NOT_EXTENDED = 510;

    /**
     * @var int
     */
    const CODE_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * @var string
     */
    const CONTENT_TYPE_JSON = 'application/json';

    /**
     * @var string
     */
    const CONTENT_TYPE_HTML = 'text/html';
}
