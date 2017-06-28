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
    /**
     * @var Product
     */
    protected $productModel;

    protected $baseUploadsDir;

    protected $baseUploadsURL;

    protected $fileSystem;

    public function __construct()
    {
        $this->setBaseModel(ProductMedia::class);
        $this->productModel   = app(Product::class);
        $this->baseUploadsDir = config('app.base_uploads_dir', base_path('public/uploads'));
        $this->baseUploadsURL = config('app.base_uploads_utl', url('uploads'));
        $this->fileSystem     = app('files');
    }

    public function attachProductMedia($productID, array $files)
    {
        $product = $this->productModel->find($productID);

        try {
            $savedFiles = $this->storeMedia($files, ['image']);
        } catch (\Exception $e) {
            throw $e;
        }

        if (count($files) !== count($savedFiles)) {
            throw new \Exception('Files upload has failed, some files are missing!');
        }

        /**
         * @var $file File
         */
        $savedMedia = [];
        foreach ($savedFiles as $file) {
            $path = str_replace('\\', '/', str_replace($this->baseUploadsDir, '', $file->getPathname()));

            $savedMedia[] = $this->getBaseModel()->create(
                [
                    'product_id'     => $product->id,
                    'type'           => $file->getType(),
                    'mime'           => $file->getMimeType(),
                    'classification' => 'original',
                    'path'           => $path,
                ]
            );
        }

        return $savedMedia;
    }

    /**
     * Store Media Files
     *
     * @param array $files
     * @param array $acceptedTypes
     *
     * @return array array file instances
     * @throws \Exception
     */
    protected function storeMedia(array $files, array $acceptedTypes = ['image'])
    {
        $uploadedFiles = [];
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                throw new \Exception('Invalid file uploaded, should be instance of '.UploadedFile::class);
            }

            $directoryName     = Carbon::today()->format('Ymd');
            $uploadToDirectory = $this->baseUploadsDir.DIRECTORY_SEPARATOR.$directoryName;

            if (! $this->validateFileType($acceptedTypes, $file)) {
                throw new \Exception('Unexpected File Type');
            }

            if (! ($savedFile = $this->saveFile($file, $uploadToDirectory))) {
                throw new \Exception('Unable to save file');
            }

            $uploadedFiles[] = $savedFile;
        }

        return $uploadedFiles;
    }

    /**
     * @param array                                       $acceptedTypes
     * @param \Symfony\Component\HttpFoundation\File\File $file
     *
     * @return bool
     */
    protected function validateFileType(array $acceptedTypes, $file)
    {
        foreach ($acceptedTypes as $type) {
            if (is_string($type) && str_contains($file->getMimeType(), $type)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Saves file to disk
     *
     * @param File $file
     * @param      $uploadToDirectory
     *
     * @return bool|File
     * @throws \Exception
     */
    protected function saveFile(File $file, $uploadToDirectory)
    {
        $fileExtension = $file->guessExtension();
        $fileName      = $file->getFilename();

        $uniqueFileName = md5($fileName.time()).'.'.$fileExtension;

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

    public function getProductMedia($productID)
    {
        $product = $this->productModel->find($productID);

        if (! $product) {
            return false;
        }

        return $this->getBaseModel()->where('product_id', '=', $product->id)->get();
    }
}