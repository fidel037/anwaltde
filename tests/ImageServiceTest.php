<?php

namespace Tests;

use App\Class\Image;
use App\Service\ImageService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ImageServiceTest extends TestCase
{
    public function testProcessFails()
    {
        $service = new ImageService();
        $this->expectException(BadRequestException::class);
        $service->process([]);
    }

    public function testDoActionCropFailsEmptyQuery()
    {
        $service = new ImageService();
        $this->expectException(BadRequestException::class);
        $this->callMethod($service, 'doAction', [[]]);
    }

    public function testDoActionCropFails()
    {
        $service = new ImageService();
        $service->setImage($this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock());
        $this->expectException(BadRequestException::class);
        $this->callMethod($service, 'doAction', [['type' => 'crop']]);
    }

    public function testDoActionInvalidTypeFails()
    {
        $service = new ImageService();
        $service->setImage($this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->onlyMethods([])
            ->getMock());
        $this->expectException(BadRequestException::class);
        $this->callMethod($service, 'doAction', [['type' => 'test']]);
    }
}
