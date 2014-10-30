<?php
use Framework\Constants\Constants;

/**
 * Class TagFactory
 */
class TagFactory
{
    /**
     * @param $name
     *
     * @return string
     */
    public function container($name)
    {
        return $this->inTags("container = $name");
    }

    /**
     * @param $expression
     *
     * @return string
     */
    public function expression($expression)
    {
        return $this->inTags("=$expression");
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function template($name)
    {
        return $this->inTags("template = $name");
    }

    /**
     * @param $expression
     *
     * @return string
     */
    public function templateModel($expression){
        return $this->inTags("templateModel = $expression");
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function content($name)
    {
        return $this->inTags("content = $name");
    }

    /**
     * @return string
     */
    public function endContent()
    {
        return $this->inTags('endcontent');
    }

    /**
     * @param string $code
     *
     * @return string
     */
    public function inTags($code)
    {
        return Constants::OPEN_TAG . $code . Constants::CLOSE_TAG;
    }
}