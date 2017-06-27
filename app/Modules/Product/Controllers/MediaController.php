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
            $this->mediaService->store($productID, $request->files->get('images'));
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, 400);
        }
    }
}
