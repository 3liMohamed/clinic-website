<?php

namespace App\Http\Controllers\Forms;

use App\Actions\Forms\StoreContactAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request, StoreContactAction $action): JsonResponse
    {
        $action->handle($request->validated());

        return response()->json([], 201);
    }
}
