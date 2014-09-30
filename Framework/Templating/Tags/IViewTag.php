<?php
namespace Framework\Templating\Tags;

/**
 * Interface IViewTag
 * @package Framework\Templating\Tags
 */
interface IViewTag {
    /**
     * @return string
     */
    public function getContents();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return string
     */
    public function getCodeBefore();

    /**
     * @return string
     */
    public function getCodeAfter();
}