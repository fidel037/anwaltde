<?php

namespace App\Service;

use App\Class\Image;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * ImageService class
 *
 */
class ImageService
{
    private Image $image;
    private $imagePath;
    private $relativePath = 'img/';

    public function __construct()
    {
        $this->imagePath = dirname(__FILE__, 2) . '/public/' . $this->relativePath;
    }

    /**
     * Process original image using provided params
     * @param array $query parameters such x,y,width,height and type
     * type: resize or crop are supported for now
     *
     */
    public function process(array $query)
    {
        if (empty($query['type']) || empty($query['width'] || empty($query['height']))) {
            throw new BadRequestException;
        }
        $imagePath = $this->imagePath . 'main.jpg';
        $this->setImage(new Image($imagePath));
        $this->doAction($query);
        $this->image->saveToFile();
    }

    /**
     * Calls actual Image methods that manipulate the image
     * @param array $query
     *
     * @throws BadRequestException
     */
    private function doAction(array $query)
    {
        if (!isset($query['type'])) {
            throw new BadRequestException;
        }
        if (method_exists($this->image, $query['type'])) {
            $this->image->{$query['type']}($query);
            return;
        }
        if ($query['type'] === 'crop') {
            if (empty($query['x']) || empty($query['y'])) {
                throw new BadRequestException;
            }
            $this->image->crop(
                $query['x'],
                $query['y'],
                $query['width'],
                $query['height']
            );
            return;
        }
        if ($query['type'] === 'resize') {
            $this->image->resize(
                $query['width'],
                $query['height']
            );
            return;
        }
        throw new BadRequestException('Type not supported');
    }

    /**
     * Creates path array of cropped and resized images
     *
     * @return array
     */
    public function getImagePaths()
    {
        $imagePaths = [];
        $files = scandir($this->imagePath);
        foreach ($files as $file) {
            $info = pathinfo($file);
            if (in_array($info['filename'], ['crop', 'resize'])) {
                $imagePaths[] = $this->relativePath.$file;
            }
        }
        return $imagePaths;
    }

    public function setImage(Image $image)
    {
        $this->image = $image;
    }
}
