<?php

namespace Awok\Modules\Product\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Product\Models\Product;
use Awok\Modules\Product\Models\ProductMedia;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaService extends BaseService
{
    protected $productModel;

    protected $baseUploadsDir;

    protected $fileSystem;

    public function __construct()
    {
        $this->setBaseModel(ProductMedia::class);
        $this->productModel   = app(Product::class);
        $this->baseUploadsDir = config('app.base_uploads_dir', base_path('public/uploads'));
        $this->fileSystem     = app('files');
    }

    public function store($productID, array $files, array $acceptedTypes = ['image'])
    {
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                throw new \Exception('Invalid file uploaded, should be instance of '.UploadedFile::class);
            }

            $fileMime = $file->getMimeType();

            $directoryName     = Carbon::today()->format('Ymd');
            $uploadToDirectory = $this->baseUploadsDir.DIRECTORY_SEPARATOR.$directoryName;

            if (! $this->validateFileType($acceptedTypes, $fileMime)) {
                throw new \Exception('Unexpected File Type');
            }

            if (! ($file = $this->saveFile($productID, $file, $uploadToDirectory))) {
                throw new \Exception('Unable to save file');
            }

            dd($file);
        }
    }

    /**
     * @param array $acceptedTypes
     * @param       $fileMime
     *
     * @return bool
     */
    protected function validateFileType(array $acceptedTypes, $fileMime)
    {
        foreach ($acceptedTypes as $type) {
            if (is_string($type) && str_contains($fileMime, $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Saves file to disk
     *
     * @param int  $productID
     * @param File $file
     * @param      $uploadToDirectory
     *
     * @return bool
     * @throws \Exception
     */
    protected function saveFile(int $productID, File $file, $uploadToDirectory)
    {
        $fileExtension = $file->getExtension();
        $fileName      = $file->getFilename();

        $uniqueFileName = md5($productID.$fileName.uniqid()).'.'.$fileExtension;

        if (! $this->fileSystem->exists($uploadToDirectory)) {
            if (! $this->fileSystem->makeDirectory($uploadToDirectory, 0755, true)) {
                throw new \Exception('Unable to make directory');
            }
        }

        if ($file->isValid() && $file = $file->move($uploadToDirectory, $uniqueFileName)) {
            return $file;
        }

        return false;
    }
}