<?php namespace Framework\Common;

use Framework\Exceptions\FileTypeNotRecognizedException;

/**
 * Class PathExtensions
 * @package Framework\Common
 */
class PathExtensions
{
    /**
     * @return string
     */
    public function joinPaths()
    {
        $args = func_get_args();
        if (sizeof($args) === 1 && is_array($args[0]))
        {
            $args = $args[0];
        }
        else
        {
            $args = $args;
        }
        $slash = DIRECTORY_SEPARATOR;
        $sections = $this->splitPath(implode('/', $args));
        return implode($slash, $sections);
    }

    /**
     * @param $path
     *
     * @return string[]
     */
    public function splitPath($path)
    {
        return preg_split(
            "@[/\\\\]@",
            $path,
            null,
            PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param $path
     * @return string
     */
    public function getExtension($path)
    {
        $split = explode('.', $path);
        if (sizeof($split) <= 1)
        {
            return null;
        }
        return $split[sizeof($split) - 1];
    }

    /**
     * @param $path
     * @return string
     * @throws FileTypeNotRecognizedException
     */
    public function getMimeType($path)
    {
        $extension = $this->getExtension($path);
        if (empty($extension))
        {
            return null;
        }
        $extension = strtolower($extension);

        $mimeType = $this->mimeTypesApplication($extension);
        if (!empty($mimeType))
        {
            return $mimeType;
        }

        $mimeType = $this->mimeTypesImage($extension);
        if (!empty($mimeType))
        {
            return $mimeType;
        }

        $mimeType = $this->mimeTypesText($extension);
        if (!empty($mimeType))
        {
            return $mimeType;
        }

        $mimeType = $this->mimeTypesVideo($extension);
        if (!empty($mimeType))
        {
            return "video/$mimeType";
        }

        $mimeType = $this->mimeTypesAudio($extension);
        if (!empty($mimeType))
        {
            return "audio/$mimeType";
        }

        throw new FileTypeNotRecognizedException($extension);
    }

    /**
     * @param $extension
     * @return string
     */
    private function mimeTypesAudio($extension)
    {
        switch ($extension)
        {
            case 'aiff':
            case 'wav':
                return $extension;
            case 'aif':
                return 'aiff';
            case 'mp3':
                return 'mpeg3';
            default:
                return null;
        }
    }

    /**
     * @param $extension
     * @return string
     */
    private function mimeTypesVideo($extension)
    {
        switch ($extension)
        {
            case 'mpeg':
                return $extension;
            case 'mpe':
            case 'mpg':
                return 'mpeg';
            case 'avi':
                return 'msvideo';
            case 'wmv':
                return 'x-ms-wmv';
            case 'mov':
                return 'quicktime';
            default:
                return null;
        }
    }

    /**
     * @param $extension
     * @return string
     */
    private function mimeTypesText($extension)
    {
        return $this->mimeTypesGeneric(
            $extension,
            'text',
            ['css', 'html'],
            [
                'html' => ['htm', 'php'],
                'plain' => ['txt']
            ]);
    }

    /**
     * @param $extension
     * @return string
     */
    private function mimeTypesImage($extension)
    {
        return $this->mimeTypesGeneric(
            $extension,
            'image',
            ['jpg', 'png', 'gif', 'bmp', 'tiff'],
            [
                'jpg' => ['jpeg', 'jpe']
            ]
        );
    }

    /**
     * @param string $extension
     * @param string $prefix
     * @param string[] $extensionTypes
     * @param string[][] $lookUp
     * @return string
     */
    private function mimeTypesGeneric($extension, $prefix, $extensionTypes, $lookUp)
    {
        if (array_search($extension, $extensionTypes) !== false)
        {
            return "$prefix/$extension";
        }
        foreach ($lookUp as $mimeType => $extensions)
        {
            if (array_search($extension, $extensions) !== false)
            {
                return "$prefix/$mimeType";
            }
        }
        return null;
    }

    /**
     * @param $extension
     * @return string
     */
    private function mimeTypesApplication($extension)
    {
        return $this->mimeTypesGeneric(
            $extension,
            'application',
            ['json', 'xml', 'rtf', 'pdf', 'zip'],
            [
                'x-javascript' => ['js'],
                'msword' => ['doc', 'docx'],
                'vnd.ms-powerpoint' => ['ppt', 'pps'],
                'x-shockwave-flash' => ['swf'],
                'x-tar' => ['tar'],
                'vnd.ms-excel' => ['xls', 'xlt', 'xlm', 'xld', 'xla', 'xlc', 'xlw', 'xll']
            ]
        );
    }
}