<?php

class KernelRepository
{
    static ?IKernel $kernel = null;

    /**
     * @return IKernel
     */
    public static function get(): IKernel
    {
        if (!self::$kernel) {
            self::$kernel = new Kernel([
                ISessionHandler::class => RedisSessionHandler::class,
                IDatabaseAccessor::class => DatabaseAccessor::class,
                IRequest::class => Request::class,
            ]);
        }

        return self::$kernel;
    }
}
