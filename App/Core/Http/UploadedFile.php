<?php

namespace App\Core\Http;

class UploadedFile
{
    private array $fileData;

    public function __construct($fileData)
    {
        $this->fileData = $fileData;
    }

    /**
     * Returns true if file uploaded correctly
     * For detailed error @return bool
     * @see UploadedFile::getError()
     */
    public function isOk(): bool
    {
        return $this->getError() == UPLOAD_ERR_OK;
    }

    /**
     * Returns the original name of the file on the client machine.
     * @return string
     */
    public function getName(): string
    {
        return $this->fileData['name'];
    }

    /**
     * Returns the mime type of the file, if the browser provided this information.
     * An example would be "image/gif". This mime type is however not checked on the PHP side
     * and therefore don't take its value for granted.
     * @return string
     */
    public function getType(): string
    {
        return $this->fileData['type'];
    }

    /**
     * Returns the size, in bytes, of the uploaded file.
     * @return int
     */
    public function getSize(): int
    {
        return $this->fileData['size'];
    }

    /**
     * Returns the temporary filename of the file in which the uploaded file was stored on the server.
     * @return string
     */
    public function getFileTempPath(): string
    {
        return $this->fileData['tmp_name'];
    }

    /**
     * Returns the size, in bytes, of the uploaded file.
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     * @return int
     */
    public function getError(): int
    {
        return $this->fileData['size'];
    }

    /**
     * Returns error message if any
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return match ($this->getError()) {
            UPLOAD_ERR_OK => null,
            UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
            UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
            UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded",
            UPLOAD_ERR_NO_FILE => "No file was uploaded",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
            UPLOAD_ERR_EXTENSION => "File upload stopped by extension",
            default => "Unknown upload error",
        };
    }

    /**
     * Stores file on server @see move_uploaded_file()
     * @param $fileName string full path to file on server
     * @return bool
     */
    public function store(string $fileName): bool
    {
        return move_uploaded_file($this->getFileTempPath(), $fileName);
    }
}