<?php

namespace Framework\Http;

/**
 * Class UploadedFile
 *
 * This class represents a file uploaded through an HTTP request. It encapsulates the details of the uploaded file,
 * providing methods to access its properties such as name, type, size, and error status.
 *
 * The class handles file upload validation, allowing users to check whether the upload was successful and to retrieve
 * meaningful error messages if any issues occurred during the upload process.
 *
 * It also provides functionality to move the uploaded file from its temporary storage location to a specified directory
 * on the server.
 **/
class UploadedFile
{
    private array $fileData;

    /**
     * Initializes an instance of UploadedFile with the provided file upload data.
     *
     * This constructor captures the data from the uploaded file as an associative array, allowing access to various
     * properties of the file such as name, type, size, etc.
     *
     * @param array $fileData An associative array containing details of the uploaded file, typically obtained from
     *                        the $_FILES superglobal.
     */
    public function __construct(array $fileData)
    {
        $this->fileData = $fileData;
    }

    /**
     * Checks whether the uploaded file was successful.
     *
     * This method evaluates the upload error code to determine if the file was uploaded without any issues.
     * A successful upload is indicated by the error code UPLOAD_ERR_OK.
     *
     * @return bool Returns true if the file upload was successful; false otherwise.
     * @see UploadedFile::getError()
     */
    public function isOk(): bool
    {
        return $this->getError() === UPLOAD_ERR_OK;
    }

    /**
     * Retrieves the original name of the uploaded file as provided by the client.
     *
     * This name is what the user sees in their file explorer when selecting the file.
     *
     * @return string The original filename from the client.
     */
    public function getName(): string
    {
        return $this->fileData['name'];
    }

    /**
     * Gets the MIME type of the uploaded file.
     *
     * This method returns the MIME type as reported by the client’s browser. Note that this value is not validated
     * server-side, so it should not be fully trusted.
     *
     * @return string The MIME type of the file (e.g., "image/gif").
     */
    public function getType(): string
    {
        return $this->fileData['type'];
    }

    /**
     * Returns the size of the uploaded file in bytes.
     *
     * This provides the size of the file as reported by the client, which can be useful for validation checks.
     *
     * @return int The size of the uploaded file in bytes.
     */
    public function getSize(): int
    {
        return $this->fileData['size'];
    }

    /**
     * Gets the temporary filename of the uploaded file on the server.
     *
     * This temporary path is where the file is stored until it is moved to its final destination. The file will be
     * removed automatically when the script ends.
     *
     * @return string The temporary filename of the uploaded file on the server.
     */
    public function getFileTempPath(): string
    {
        return $this->fileData['tmp_name'];
    }

    /**
     * Retrieves the error code associated with the file upload.
     *
     * This method allows checking if any errors occurred during the file upload process, which can be useful for
     * debugging and error handling.
     *
     * @return int The error code for the file upload.
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     */
    public function getError(): int
    {
        return $this->fileData['error'];
    }

    /**
     * Provides a human-readable error message corresponding to the file upload error code.
     *
     * This method maps the upload error codes to descriptive messages, making it easier to understand the nature of any
     * upload issues that occurred.
     *
     * @return string|null A description of the error, or null if no error occurred.
     * @see https://www.php.net/manual/en/features.file-upload.errors.php
     */
    public function getErrorMessage(): ?string
    {
        return match ($this->getError()) {
            UPLOAD_ERR_OK => null,
            UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
            UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive specified in the HTML form.",
            UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
            UPLOAD_ERR_NO_FILE => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder for file uploads.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write the file to disk.",
            UPLOAD_ERR_EXTENSION => "File upload stopped by a PHP extension.",
            default => "An unknown error occurred during file upload.",
        };
    }

    /**
     * Moves the uploaded file from its temporary location to a specified path on the server.
     *
     * This method uses PHP's move_uploaded_file() function to handle the file transfer, which is essential for securely
     * managing uploaded files.
     *
     * @param string $fileName The full path where the file should be saved on the server.
     * @return bool Returns true on success; false on failure.
     * @see move_uploaded_file()
     */
    public function store(string $fileName): bool
    {
        return move_uploaded_file($this->getFileTempPath(), $fileName);
    }
}
