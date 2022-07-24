<?php

namespace Tests;

use App\Class\Image;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class ImageTest extends TestCase
{
    private $imagePath = '/tmp/test.jpeg';
    private $image;

    public function setUp(): void
    {
        parent::setUp();
        $image = imagecreate(1, 1);
        imagejpeg($image, $this->imagePath);
        $this->image = new Image($this->imagePath);
    }

    public function testSetPathFails()
    {
        $image = $this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->expectException(FileNotFoundException::class);
        $this->callMethod($image, 'setPath', ['test']);
    }

    public function testSetPathPass()
    {
        $image = $this->getMockBuilder(Image::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->callMethod($image, 'setPath', [__FILE__]);
        $this->assertTrue(true);
    }

    public function testCrop()
    {
        $this->image->crop([
            'x' => 1,
            'y' => 2,
            'width' => 3,
            'height' => 4
        ]);
        $this->assertTrue(true);
        $this->removeImage();
    }

    public function testResize()
    {
        $this->image->resize([
            'width' => 3,
            'height' => 4
        ]);
        $this->assertTrue(true);
        $this->removeImage();
    }

    public function testSaveToFile()
    {
        $this->image->resize([
            'width' => 3,
            'height' => 4
        ]);
        $this->assertTrue(true);
        $this->image->saveToFile();
        $this->assertTrue(file_exists('/tmp/resize.jpeg'));

        $this->image->crop([
            'x' => 1,
            'y' => 2,
            'width' => 3,
            'height' => 4
        ]);
        $this->assertTrue(true);
        $this->image->saveToFile();
        $this->assertTrue(file_exists('/tmp/crop.jpeg'));

        $this->removeImage();
    }

    public function testGetBase64Encoded()
    {
        $response = $this->image->getBase64Encoded();
        $this->assertNotEmpty($response);
        $this->assertEquals('/9j/4AAQSkZJRgABAQEAYABgAAD//gA+Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2ODApLCBkZWZhdWx0IHF1YWxpdHkK/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAAQABAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A+f6KKKAP/9k=', $response);
        $this->removeImage();
    }

    public function testGetNewFilename()
    {
        $response = $this->callMethod($this->image, 'getNewFilename', []);
        $this->assertEquals('/tmp/.png', $response);
        $this->removeImage();
    }

    public function testGetExtension()
    {
        $extension = $this->callMethod($this->image, 'getExtension', []);
        $this->assertEquals('jpeg', $extension);
    }

    private function removeImage()
    {
        unlink($this->imagePath);
        @unlink('/tmp/resize.jpeg');
        @unlink('/tmp/convert.jpeg');
    }
}
