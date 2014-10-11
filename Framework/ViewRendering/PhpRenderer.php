<?php
namespace Framework\ViewRendering;

/**
 * Class PhpRenderer
 * @package Framework\ViewRendering
 */
class PhpRenderer
{
    /** @var array */
    private $variables = array();

    /**
     * @param string $code
     *
     * @throws \Exception
     * @return string
     */
    public function renderToHtml($code)
    {
        foreach ($this->variables as $name => $value)
        {
            eval('$' . $name . ' = $value;');
        }
        $temporaryFile = tmpfile();
        fwrite($temporaryFile, $code);
        $temporaryFilename = stream_get_meta_data($temporaryFile)['uri'];
        ob_start();
        try
        {
            include $temporaryFilename;
        }
        catch (\Exception $e)
        {
        }
        if (empty($e))
        {
            return ob_get_clean();
        }
        throw $e;
    }

    /**
     * @param string $name
     * @param object $value
     */
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }
}