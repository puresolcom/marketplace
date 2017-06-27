<?php
namespace Awok\Modules\Product\Models;

use Awok\Core\Eloquent\Model;

class ProductMedia extends Model
{
    public $table = 'products_medias';

    public $protected = true;

    public $ownerKey = 'product.store.user_id';

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}