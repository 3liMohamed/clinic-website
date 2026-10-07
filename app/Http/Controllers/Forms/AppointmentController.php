<?php

namespace App\Http\Controllers\Forms;

use App\Actions\Forms\AppointmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use Illuminate\Http\JsonResponse;

class AppointmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreAppointmentRequest $request, AppointmentAction $action): JsonResponse
    {
        $action->handle($request->validated());

        return response()->json([], 201);
    }
}
