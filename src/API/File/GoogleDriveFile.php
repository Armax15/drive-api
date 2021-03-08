<?php


namespace core\API\File;


use Google_Service_Drive_DriveFile;
use InvalidArgumentException;
use LogicException;

class GoogleDriveFile implements File
{
    public const DIR_MIME_TYPE = 'application/vnd.google-apps.folder';

    private Google_Service_Drive_DriveFile $file;
    private array $options = [];
    private array $unWritableOptions = ['fileExtension'];

    /**
     * @param string $name
     *
     * @return static
     */
    public static function getDirFileObject(string $name): self
    {
        $file = new self;
        $file->file = new Google_Service_Drive_DriveFile(
            [
                'name' => $name,
                'mimeType' => self::DIR_MIME_TYPE,
            ]
        );

        return $file;
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return static
     */
    public static function getUploadFileObject(string $name, string $path): self
    {
        if (!is_readable($path)) {
            throw new InvalidArgumentException("File from path [{$path}] is not readable.");
        }

        $file = new self;
        $file->file = new Google_Service_Drive_DriveFile(['name' => $name]);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            throw new LogicException("Failed getting mime-type from file [{$path}].");
        }

        $file->options = [
            'data'       => file_get_contents($path),
            'mimeType'   => finfo_file($finfo, $path),
            'uploadType' => 'media'
        ];

        return $file;
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