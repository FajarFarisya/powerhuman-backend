<?php

namespace App\Http\Controllers\API;

use App\Models\Responsibility;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;
use Exception;

class ResponsibilityController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $responsibilityQuery = Responsibility::query();

        // Get single data
        if ($id) {
            $responsibility = $responsibilityQuery->find($id);

            if ($responsibility) {
                return ResponseFormatter::success($responsibility, 'Responsibilities found');
            }

            return ResponseFormatter::error('Role not found', 404);
        }

        // Get multiple data
        $responsibilitys = $responsibilityQuery->where('role_id', $request->role_id);

        if ($name) {
            $responsibilitys->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $responsibilitys->paginate($limit),
            'Responsibilities found'
        );
    }

    public function create(CreateResponsibilityRequest $request)
    {
        try {
            // Create role
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            if (!$responsibility) {
                throw new Exception('Responsibility not created');
            }

            return ResponseFormatter::success($responsibility, 'Responsibility created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $responsibility = Responsibility::find($id);

            //TODO: Check if role is owned by user

            // Check if role exists
            if (!$responsibility) {
                throw new Exception('Responsibility not created');
            }

            $responsibility->delete();

            return ResponseFormatter::success('Responsibility deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
