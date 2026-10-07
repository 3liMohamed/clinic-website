<?php

namespace App\Http\Controllers\Forms;

use App\Actions\Forms\SubscribeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscribeRequest;
use Illuminate\Http\JsonResponse;

class SubscribeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SubscribeRequest $request, SubscribeAction $action): JsonResponse
    {
        $action->handle($request->validated('email'));
        return response()->json([], 201);
    }
}
