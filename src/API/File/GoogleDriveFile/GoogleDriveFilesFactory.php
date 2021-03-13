<?php


namespace core\API\File\GoogleDriveFile;


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
            [
                GoogleDriveFile::FIELD_NAME => $name,
            ],
            [
                GoogleDriveFile::FIELD_DATA         => file_get_contents($path),
                GoogleDriveFile::FIELD_MIME_TYPE    => finfo_file($finfo, $path),
                GoogleDriveFile::FIELD_UPLOAD_TYPE  => GoogleDriveFile::UPLOAD_TYPE_MEDIA,
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
        return new GoogleDriveFile([GoogleDriveFile::FIELD_ID => $fileId]);
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
                GoogleDriveFile::FIELD_DATA      => $name,
                GoogleDriveFile::FIELD_MIME_TYPE => GoogleDriveFile::DIR_MIME_TYPE,
            ]
        );
    }
}