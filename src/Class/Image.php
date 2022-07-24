<?php

namespace App\Class;

use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use GdImage;

/**
 * Image class
 *
 */
class Image
{
    private $extension;
    private $path;
    private GdImage|bool $gdObject;
    private $actionType;

    /**
     * @param string $path absoulute path to image
     *
     */
    public function __construct($path)
    {
        $this->setPath($path);
        $this->extension = $this->getExtension();
        $this->phpLoadImage();
    }

    /**
     * Check if file exists and if it does set path property
     * @param string $path
     *
     * @throws FileNotFoundException
     */
    private function setPath($path)
    {
        if (file_exists($path)) {
            $this->path = $path;
            return;
        }
        throw new FileNotFoundException($path);
    }

    /**
     * calls imagecreatefrom* function and created Gd object
     * using the path of the image
     *
     */
    private function phpLoadImage()
    {
        $fnName = 'imagecreatefrom' . $this->extension;
        if (function_exists($fnName)) {
            $this->gdObject = $fnName($this->path);
            return;
        }

        $this->gdObject = imagecreatefromstring(file_get_contents($this->path));
    }

    /**
     * Crops the image using provided parameters
     *
     * @param array $params
     *
     * @throws Exception
     */
    public function crop(array $params = [])
    {
        if (
            empty($params['width']) ||
            empty($params['height']) ||
            empty($params['x']) ||
            empty($params['y'])

        ) {
            throw new BadRequestException;
        }
        $this->actionType = 'crop';
        $this->gdObject = imagecrop(
            $this->gdObject,
            [
                'x' => $params['x'],
                'y' => $params['y'],
                'width' => $params['width'],
                'height' => $params['height']
            ]
        );
        if ($this->gdObject === false) {
            throw new \Exception('Cropping failure');
        }
    }

    /**
     * Resizes the image using provided parameters
     *
     * @param int $width
     * @param int $height
     *
     * @throws Exception
     */
    public function resize(array $params = [])
    {
        if (empty($params['width']) || empty($params['height'])) {
            throw new BadRequestException;
        }
        $this->actionType = 'resize';
        $resized = imagescale($this->gdObject, $params['width'], $params['height']);
        $this->gdObject = $resized;
        if ($this->gdObject === false) {
            throw new \Exception('Resize failure');
        }
    }

    /**
     * Saves resized/cropped image to file
     * crop - saves to crop.extension
     * resize - saves to resize.extension
     *
     */
    public function saveToFile()
    {
        $fnName = 'image' . $this->extension;
        $filename = $this->getNewFilename($this->extension);
        if (function_exists($fnName)) {
            $fnName($this->gdObject, $filename);
            return;
        }
        imagepng($this->gdObject, $filename);
    }

    /**
     * returns base64 encoded image
     *
     */
    public function getBase64Encoded()
    {
        $fnName = 'image' . $this->extension;
        ob_start();
        if (function_exists($fnName)) {
            $fnName($this->gdObject);
            $image = ob_get_contents();
        }
        if (!isset($image)) {
            imagepng($this->gdObject);
            $image = ob_get_contents();
        }
        ob_end_clean();
        return base64_encode($image);
    }

    /**
     * @param $extension default png
     *
     * @return string absolute path for the image
     */
    private function getNewFilename($extension = 'png')
    {
        return dirname($this->path) . '/' . $this->actionType . '.' . $extension;
    }

    /**
     * Extracts extension from file path
     * If extension is jpg it switches to jpeg
     * because php does not have imagejpg, imagecreatefromjpg functions
     *
     * @return string extension
     */
    private function getExtension()
    {
        $extension = pathinfo($this->path, PATHINFO_EXTENSION);
        if ($extension === 'jpg') {
            $extension = 'jpeg';
        }
        return $extension;
    }
}
