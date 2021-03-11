<?php


namespace core\API\File;


use InvalidArgumentException;
use LogicException;

class GoogleDriveFilesFactory
{

    /**
     * @param string $name
     * @param string $path
     *
     * @return GoogleDriveFile
     */
    public static function buildFileForUploading(string $name, string $path): GoogleDriveFile
    {
        if (!is_readable($path)) {
            throw new InvalidArgumentException("File from path [{$path}] is not readable.");
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            throw new LogicException("Failed getting mime-type from file [{$path}].");
        }

        return new GoogleDriveFile(
            ['name' => $name],
            [
                'data'       => file_get_contents($path),
                'mimeType'   => finfo_file($finfo, $path),
                'uploadType' => 'media'
            ]
        );
    }

    /**
     * @param string $fileId
     *
     * @return GoogleDriveFile
     */
    public static function buildFileWithId(string $fileId): GoogleDriveFile
    {
        return new GoogleDriveFile(['id' => $fileId]);
    }

    /**
     * @param string $name
     *
     * @return GoogleDriveFile
     */
    public static function buildDirFile(string $name): GoogleDriveFile
    {
        return new GoogleDriveFile(
            [
                'name' => $name,
                'mimeType' => GoogleDriveFile::DIR_MIME_TYPE,
            ]
        );
    }
}