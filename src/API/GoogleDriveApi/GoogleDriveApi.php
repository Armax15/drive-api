<?php


namespace core\API\GoogleDriveApi;


use core\API\APIService;
use core\API\APISettings\ClientSettings;
use core\API\File\File;
use core\API\Params;
use core\API\ServiceResponse;
use Google_Client;
use Google_Service_Drive;
use RuntimeException;

class GoogleDriveApi implements APIService
{
    public const SERVICE_NAME = 'GoogleDriveApi';

    private ClientSettings $clientSettings;
    private Google_Client $client;
    private Google_Service_Drive $service;
    private array $availableParams = ['q', 'file'];

    public function __construct(ClientSettings $settings)
    {
        $this->clientSettings = $settings;
    }

    /**
     * @return Google_Service_Drive
     * @throws \Google\Exception
     */
    private function getGoogleDriveService(): Google_Service_Drive
    {
        if (!isset($this->service)) {
            $this->createClientIfNotExists();
            $this->setupToken();
            $this->service = new Google_Service_Drive($this->client);
        }

        return $this->service;
    }

    /**
     * @throws \Google\Exception
     */
    private function createClientIfNotExists(): void
    {
        if (isset($this->client)) {
            return;
        }

        $this->client = new Google_Client();
        $this->client->setApplicationName($this->clientSettings->getAppName());
        $this->client->setScopes($this->clientSettings->getScopes());
        $this->client->setAuthConfig($this->clientSettings->getAuthData());
        $this->client->setAccessType($this->clientSettings->getAccessType());
        $this->client->setPrompt('select_account consent');
    }

    /**
     * @return bool
     */
    private function setupToken(): bool
    {
        $tokenPath = $this->clientSettings->getTokenPath();
        $isTokenFileExists = file_exists($tokenPath);
        if ($isTokenFileExists) {
            $tokenArray = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($tokenArray);
        }

        if (!$this->client->isAccessTokenExpired()) {

            return true;
        }

        $tokenDir = dirname($tokenPath);
        if (
            !$isTokenFileExists
            && !file_exists($tokenDir)
            && !mkdir($tokenDir, 0600, true)
            && !is_dir($tokenDir)
        ) {
            throw new RuntimeException(sprintf('Directory [%s] was not created', $tokenDir));
        }

        $token = json_encode($this->getTokenArray());

        return file_put_contents($tokenPath, $token) > 0;
    }

    /**
     * @return array
     */
    private function getTokenArray(): array
    {
        if ($this->client->getRefreshToken()) {
            return $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        }

        $authUrl = $this->client->createAuthUrl();
        printf("Open the following link in your browser:\n%s\n", $authUrl);
        print 'Enter verification code: ';
        $authCode = trim(fgets(STDIN));

        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
        $this->client->setAccessToken($accessToken);

        if (array_key_exists('error', $accessToken)) {
            throw new RuntimeException(implode(', ', $accessToken));
        }

        return $accessToken;
    }

    /**
     * @return Params
     */
    public function initEmptyParams(): Params
    {
        return new Params($this->availableParams);
    }

    public function searchFiles(Params $params): ServiceResponse
    {
        $filesList = $this->getGoogleDriveService()->files->listFiles($params->toArray());
        if ($filesList->count() < 1) {
            return new ServiceResponse([]);
        }

        $files = [];
        foreach ($filesList->getFiles() as $file) {
            $files[] = [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'mimeType' => $file->getMimeType(),
                'kind' => $file->getKind(),
                'modifiedAt' => $file->getModifiedTime(),
                'size' => $file->getSize(),
            ];
        }

        return new ServiceResponse($files);
    }

    public function createFile(File $file): ServiceResponse
    {
        $driveFile = $this->getGoogleDriveService()->files->create($file->getFile(), $file->getOptions());

        return new ServiceResponse([
            'id' => $driveFile->getId(),
            'name' => $driveFile->getName(),
            'mimeType' => $driveFile->getMimeType(),
            'kind' => $driveFile->getKind(),
            'modifiedAt' => $driveFile->getModifiedTime(),
            'size' => $driveFile->getSize(),
        ]);
    }

    public function deleteFile(File $file): ServiceResponse
    {
        $fileId = $this->getFileIdOrThrowException($file);
        $response = $this->getGoogleDriveService()->files->delete($fileId, $file->getOptions());
        $status = $response->getStatusCode() === 204 ? ServiceResponse::SUCCESS_STATUS : ServiceResponse::FAIL_STATUS;

        return new ServiceResponse([], $status);
    }

    public function updateFile(File $file): ServiceResponse
    {
        $fileId = $this->getFileIdOrThrowException($file);
        $driveFile = $this->getGoogleDriveService()->files->update($fileId, $file->getFile(), $file->getOptions());

        return new ServiceResponse([
            'id' => $driveFile->getId(),
            'name' => $driveFile->getName(),
            'mimeType' => $driveFile->getMimeType(),
            'kind' => $driveFile->getKind(),
            'modifiedAt' => $driveFile->getModifiedTime(),
            'size' => $driveFile->getSize(),
        ]);
    }

    public function downloadFile(File $file): ServiceResponse
    {
        $fileId = $this->getFileIdOrThrowException($file);
        $saveTo = $file->getOptions()['saveTo'] ?? null;
        if (empty($saveTo)) {
            throw new \InvalidArgumentException("Missed required file option [saveTo].");
        }

        $response = $this->getGoogleDriveService()->files->get($fileId, ['alt' => 'media']);
        if (empty($response)) {
            throw new \LogicException("Empty response.");
        }

        $written = (int) file_put_contents($saveTo, $response->getBody()->getContents());

        return new ServiceResponse([
            'path' => $saveTo,
            'size' => $written
        ]);
    }

    public static function getServiceName(): string
    {
        return self::SERVICE_NAME;
    }

    private function getFileIdOrThrowException(File $file): string
    {
        $fileId = $file->getFile()->getId();
        if (empty($fileId)) {
            throw new \InvalidArgumentException("File doesn't contain id field.");
        }

        return $fileId;
    }
}