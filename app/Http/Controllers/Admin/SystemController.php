<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SystemController extends Controller
{
    public function index()
    {
        $failedJobs = DB::table('failed_jobs')->latest()->get();
        $jobs = DB::table('jobs')->get();
        
        return Inertia::render('Admin/System/Index', [
            'failedJobs' => $failedJobs,
            'jobsCount' => $jobs->count(),
            'jobs' => $jobs,
        ]);
    }

    public function clearFailedJobs()
    {
        DB::table('failed_jobs')->delete();
        return back()->with('message', 'Failed jobs cleared.');
    }
}
