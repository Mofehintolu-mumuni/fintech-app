<?php

namespace App\Interfaces;

interface HttpStatusCode {
    const OK = 200;
    const CREATED = 201;
    const UPDATED = 202;
    const DELETED = 200;
    const VALIDATION = 422;
    const UNAUTHORIZED = 401;
    const NO_CONTENT = 204;
    const SERVER_ERROR = 500;
    const FORBIDDEN = 403;
    const BAD_RESPONSE = 407;
    const BAD_REQUEST = 400;
    const NOT_FOUND = 404;
}