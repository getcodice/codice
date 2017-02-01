<?php

namespace Codice\Support\Traits;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;

trait ValidatesRequests
{
    public function validate(Request $request, array $rules, $targetRoute = null, array $messages = [])
    {
        $validator = app(Factory::class)->make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $targetUrl = $targetRoute ? route($targetRoute) : app(UrlGenerator::class)->previous();
            $response = redirect()->to($targetUrl)->withInput($request->input())->withErrors($validator);

            throw new HttpResponseException($response);
        }
    }
}
