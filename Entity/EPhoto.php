<?php
class EPhoto {
    private $file;
    private $fileName;
    private $fileTmpName;
    private $fileSize;
    private $fileType;
    private $fileError;
    private $targetDir;
    private $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];

    public function __construct($file, $targetDir = "uploads/") {
        $this->file = $file;
        $this->fileName = basename($file["name"]);
        $this->fileTmpName = $file["tmp_name"];
        $this->fileSize = $file["size"];
        $this->fileType = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
        $this->fileError = $file["error"];
        $this->targetDir = $targetDir;
    }

    public function upload() {
        if ($this->fileError !== UPLOAD_ERR_OK) {
            return "Error during file upload.";
        }

        if (!$this->isValidImage()) {
            return "File is not a valid image.";
        }

        if ($this->fileExists()) {
            return "Sorry, file already exists.";
        }

        if (!$this->isAllowedFileType()) {
            return "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if ($this->moveFile()) {
            return "The file ". htmlspecialchars($this->fileName) . " has been uploaded.";
        } else {
            return "Sorry, there was an error uploading your file.";
        }
    }

    public function resize($newWidth, $newHeight) {
        list($width, $height) = getimagesize($this->fileTmpName);

        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        switch ($this->fileType) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($this->fileTmpName);
                break;
            case 'png':
                $source = imagecreatefrompng($this->fileTmpName);
                break;
            case 'gif':
                $source = imagecreatefromgif($this->fileTmpName);
                break;
            default:
                return "Unsupported file type.";
        }

        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        $this->saveImage($thumb);

        imagedestroy($source);
        imagedestroy($thumb);

        return "Image has been resized.";
    }

    private function isValidImage() {
        $check = getimagesize($this->fileTmpName);
        return $check !== false;
    }

    private function fileExists() {
        return file_exists($this->targetDir . $this->fileName);
    }

    private function isAllowedFileType() {
        return in_array($this->fileType, $this->allowedFileTypes);
    }

    private function moveFile() {
        return move_uploaded_file($this->fileTmpName, $this->targetDir . $this->fileName);
    }

    private function saveImage($image) {
        $filePath = $this->targetDir . $this->fileName;
        switch ($this->fileType) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($image, $filePath);
                break;
            case 'png':
                imagepng($image, $filePath);
                break;
            case 'gif':
                imagegif($image, $filePath);
                break;
        }
    }
}