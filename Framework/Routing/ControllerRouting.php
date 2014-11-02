<?php namespace Framework\Routing;

use Framework\ViewRendering\IFileReader;

/**
 * Class ControllerRouting
 * @package Framework\Routing
 */
class ControllerRouting
{
    private $request;

    /**
     * @param IRequest    $request
     * @param IFileReader $controllers
     */
    public function __construct(IFileReader $controllers, IRequest $request = null)
    {
        if ($request === null)
        {
            $request = new Request();
        }
        $this->request = $request;
    }

    /**
     * @return IRequest|Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}