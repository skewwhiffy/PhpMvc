<?php namespace Framework\Routing;

/**
 * Class RequestMethod
 */
abstract class RequestMethod
{
    const GET = 0;
    const POST = 1;
    const PUT = 2;
    const PATCH = 3;
    const DELETE = 4;
    const COPY = 5;
    const HEAD = 6;
    const OPTIONS = 7;
    const LINK = 8;
    const UNLINK = 9;
    const PURGE = 10;
    const TRACE = 11;
    const CONNECT = 12;
}