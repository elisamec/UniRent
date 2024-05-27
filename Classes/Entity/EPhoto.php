<?php
//Da rivedere
class EPhoto {
    private int $id;

    public function getId():int {
        return $this->id;
    }

    /*
    private array $file; // Holds the uploaded file information
    private string $fileName; // Holds the name of the uploaded file
    private string $fileTmpName; // Holds the temporary path of the uploaded file
    private int $fileSize; // Holds the size of the uploaded file
    private string $fileType; // Holds the MIME type of the uploaded file
    private int $fileError; // Holds any error code associated with the file upload
    private string $targetDir; // The directory where the file will be moved
    private array $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file types for upload

    // Constructor to initialize the class with the uploaded file and target directory
    public function __construct(array $file, string $targetDir = "uploads/") {
        $this->file = $file;
        $this->fileName = basename($file["name"]);
        $this->fileTmpName = $file["tmp_name"];
        $this->fileSize = (int) $file["size"];
        $this->fileType = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
        $this->fileError = (int) $file["error"];
        $this->targetDir = $targetDir;
    }

    // Method to handle the file upload process
    public function upload(): string {
        if ($this->fileError !== UPLOAD_ERR_OK) {
            return "Error during file upload."; // Check for upload errors
        }

        if (!$this->isValidImage()) {
            return "File is not a valid image."; // Validate the image file
        }

        if ($this->fileExists()) {
            return "Sorry, file already exists."; // Check if the file already exists
        }

        if (!$this->isAllowedFileType()) {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed."; // Validate the file type
        }

        if ($this->moveFile()) {
            return "The file ". htmlspecialchars($this->fileName) . " has been uploaded."; // Move the file to the target directory
        } else {
            return "Sorry, there was an error uploading your file."; // Handle file move errors
        }
    }

    // Method to resize the image
    public function resize(int $newWidth, int $newHeight): string {
        list($width, $height) = getimagesize($this->fileTmpName); // Get original dimensions

        $thumb = imagecreatetruecolor($newWidth, $newHeight); // Create a new true color image
        switch ($this->fileType) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($this->fileTmpName); // Create image from JPEG
                break;
            case 'png':
                $source = imagecreatefrompng($this->fileTmpName); // Create image from PNG
                break;
            case 'gif':
                $source = imagecreatefromgif($this->fileTmpName); // Create image from GIF
                break;
            default:
                return "Unsupported file type."; // Handle unsupported file types
        }

        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height); // Resize the image
        $this->saveImage($thumb); // Save the resized image

        imagedestroy($source); // Free up memory
        imagedestroy($thumb); // Free up memory

        return "Image has been resized."; // Return success message
    }

    // Method to validate if the file is an actual image
    private function isValidImage(): bool {
        $check = getimagesize($this->fileTmpName);
        return $check !== false;
    }

    // Method to check if the file already exists in the target directory
    private function fileExists(): bool {
        return file_exists($this->targetDir . $this->fileName);
    }

    // Method to validate the file type
    private function isAllowedFileType(): bool {
        return in_array($this->fileType, $this->allowedFileTypes, true);
    }

    // Method to move the uploaded file to the target directory
    private function moveFile(): bool {
        // Ensure the target directory exists
        if (!is_dir($this->targetDir)) {
            mkdir($this->targetDir, 0777, true); // Create the directory if it doesn't exist
        }
        return move_uploaded_file($this->fileTmpName, $this->targetDir . $this->fileName);
    }

    // Method to save the resized image
    private function saveImage($image): void {
        $filePath = $this->targetDir . $this->fileName;
        switch ($this->fileType) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $filePath); // Save as JPEG
                break;
            case 'png':
                imagepng($image, $filePath); // Save as PNG
                break;
            case 'gif':
                imagegif($image, $filePath); // Save as GIF
                break;
        }
    }
    */
}
