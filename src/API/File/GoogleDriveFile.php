<?php


namespace core\API\File;


use Google_Service_Drive_DriveFile;
use InvalidArgumentException;
use LogicException;

class GoogleDriveFile implements File
{
    public const DIR_MIME_TYPE = 'application/vnd.google-apps.folder';

    private Google_Service_Drive_DriveFile $file;
    private array $options;
    private array $unWritableOptions = ['fileExtension'];


    public function __construct(array $fileProperties, array $options = [])
    {
        $this->filterUnWritableOptions($fileProperties);
        $this->file = new Google_Service_Drive_DriveFile($fileProperties);
        $this->options = $options;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function addOptions(array $options): self
    {
        $this->filterUnWritableOptions($options);
        $this->options = array_replace($this->options, $options);

        return $this;
    }

    /**
     * @param array $options
     */
    private function filterUnWritableOptions(array &$options): void
    {
        foreach ($this->unWritableOptions as $optionName) {
            if (isset($options[$optionName])) {
                unset($options[$optionName]);
            }
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return Google_Service_Drive_DriveFile
     */
    public function getFile(): Google_Service_Drive_DriveFile
    {
        return $this->file;
    }
}