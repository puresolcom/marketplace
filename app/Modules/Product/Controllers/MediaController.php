<?php
namespace Awok\Modules\Product\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Product\Services\MediaService;

class MediaController extends Controller
{
    /**
     * @var MediaService
     */
    protected $mediaService;

    public function __construct()
    {
        $this->mediaService = app('media');
    }

    public function store(Request $request, $productID)
    {
        if (! $request->files->has('images')) {
            return $this->jsonResponse(null, 'No media was sent to be uploaded', 400);
        }

        try {
            $attachedMedia = $this->mediaService->attachProductMedia($productID, $request->files->get('images'));
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, 400);
        }

        return $this->jsonResponse($attachedMedia, 'Media successfully attached to product', 200);
    }

    public function get($productID)
    {

        try {
            $productMedia = $this->mediaService->getProductMedia($productID);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, 400);
        }

        return $this->jsonResponse($productMedia, 'Product media retrieved successfully', 200);
    }
}
