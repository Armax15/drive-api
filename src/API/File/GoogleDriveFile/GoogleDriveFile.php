<?php


namespace core\API\File\GoogleDriveFile;


use core\API\File\File;
use Google_Service_Drive_DriveFile;

class GoogleDriveFile implements File
{
    public const DIR_MIME_TYPE = 'application/vnd.google-apps.folder';

    public const FIELD_ID          = 'id';
    public const FIELD_NAME        = 'name';
    public const FIELD_DATA        = 'data';
    public const FIELD_MIME_TYPE   = 'mimeType';
    public const FIELD_UPLOAD_TYPE = 'uploadType';

    public const UPLOAD_TYPE_MEDIA = 'media';

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