<?php


class UpdateProductBrandNameCommand implements Command {
    private ProductController $controller;
    private Middleware $jwtMiddleware;
    function __construct(ProductController $cont, Middleware $middleware)
    {
        $this->controller = $cont;
        $this->jwtMiddleware = $middleware;
    }

    function process($params = [])
    {
            $this->jwtMiddleware->linkWith(new IsAdminMiddleware());
            $this->jwtMiddleware->check();
            return $this->controller->updateProductBrandName($params[0]);
    }
}