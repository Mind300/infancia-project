<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Nurseries;
use Illuminate\Http\Request;

class SuperAdminStatistics extends Controller
{
    /**
     * SuperAdmin Statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function superAdminStatistics()
    {
        // Nurseries Accepted
        $nurseries_accepted_count = Nurseries::where('status', 'accepted')->count();
        // Nurseries Count Pending
        $nurseries_pending_count = Nurseries::where('status', 'pending')->count();
        // Nurseries Total Kids Count
        $total_kids_count = Nurseries::withCount('kids')->get()->setVisible(['id', 'name', 'kids_count']);
        // Nurseries Count Pending
        $employees_count = 0;

        $data = [
            'nursery_accepted' => $nurseries_accepted_count,
            'nursery_pending' => $nurseries_pending_count,
            'employees_count' => $employees_count,
            'nurseries_childs' => $total_kids_count,
        ];
        return contentResponse($data, 'SuperAdmin Statistics');
    }
}
