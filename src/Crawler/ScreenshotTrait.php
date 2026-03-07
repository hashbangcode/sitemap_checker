<?php

namespace Hashbangcode\SitemapChecker\Crawler;

trait ScreenshotTrait
{

    final public const SCREENSHOT_TYPE_DATA = 'data';
    final public const SCREENSHOT_TYPE_FILE = 'file';

    protected bool $takeScreenshot = false;

    protected string $screenshotType;

    protected string $fileName;

    public function canTakeScreenshot(): bool
    {
        return true;
    }

    public function takescreenshot(): bool
    {
        return $this->takeScreenshot;
    }

    public function setTakeScreenshot(bool $takeScreenshot): self
    {
        $this->takeScreenshot = $takeScreenshot;
        return $this;
    }

    /**
     * @return string
     */
    public function getScreenshotType(): string
    {
        if (!isset($this->screenshotType)) {
            $this->screenshotType = static::SCREENSHOT_TYPE_DATA;
        }
        return $this->screenshotType;
    }

    /**
     * @param string $screenshotType
     * @return self
     */
    public function setScreenshotType(string $screenshotType): self
    {
        $this->screenshotType = $screenshotType;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return self
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

}