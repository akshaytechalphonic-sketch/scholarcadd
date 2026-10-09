<?php

namespace Modules\Course\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Traits\ApiResponseTrait;
use Modules\Course\Models\CourseCountry;
use Modules\DynamicMaster\Models\State;
use Modules\Course\Models\PromoCode;


class PromoCodeController extends Controller
{
    use ApiResponseTrait;

    public function Promo_list(Request $request)
    {
        try {
            $query = PromoCode::query()
                ->where('status', 'active')
                ->where('promo_type' , 'public')
                ->select('id', 'promocode', 'text',
                        'discount_percentage', 'course_type' , 'status', 'start_date', 'end_date');

            // Filters
            if ($request->filled('course_id')) {
                $query->where('course_id', $request->course_id);
            }

            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }

            $query->where(function($q) {
                $q->whereNull('start_date')
                ->orWhere('start_date', '<=', now());
            })->where(function($q) {
                $q->whereNull('end_date')
                ->orWhere('end_date', '>=', now());
            });

            $promos = $query->get();

            return response()->json([
                'status' => true,
                'message' => 'Active promo codes fetched successfully',
                'data' => $promos,
                'total' => $promos->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch promo codes: ' . $e->getMessage(),
            ], 500);
        }
    }

public function verifyPromoCode(Request $request)
{
    $request->validate([
        'promocode' => 'required|string|max:12',
        'course_id' => 'sometimes|nullable|exists:tbl_courses,id',
    ]);

    try {

        $promo = PromoCode::where('promocode', $request->promocode)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->when($request->filled('course_id'), function ($q) use ($request) {
                $q->where(function ($subQ) use ($request) {
                    $subQ->whereNull('course_id')
                         ->orWhere('course_id', $request->course_id);
                });
            })
            ->first();

        if (!$promo) {
            return response()->json([
                'status' => false,
                'valid' => false,
                'message' => 'Invalid promo code',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'valid' => true,
            'message' => 'Promo code verified successfully',
            'data' => $promo
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'valid' => false,
            'message' => 'Verification failed',
            'error' => $e->getMessage()
        ], 500);
    }
}





}
