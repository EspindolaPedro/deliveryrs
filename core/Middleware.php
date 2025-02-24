<?php
namespace core;

abstract class Middleware {
    abstract public function handle($request, $next);
}
