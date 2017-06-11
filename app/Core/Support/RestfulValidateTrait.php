<?php

namespace Awok\Core\Support;

use Illuminate\Http\Request;

trait RestfulValidateTrait
{
    function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        return $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);
    }
}