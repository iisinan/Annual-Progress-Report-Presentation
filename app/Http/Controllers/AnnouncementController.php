<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')->latest()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Created Announcement: ' . $request->title,
            'model_type' => 'Announcement',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Announcement created successfully.');
    }

    public function destroy(Request $request, Announcement $announcement)
    {
        $title = $announcement->title;
        $announcement->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Deleted Announcement: ' . $title,
            'model_type' => 'Announcement',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Announcement deleted successfully.');
    }

    public function toggleActive(Request $request, Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        $status = $announcement->is_active ? 'Activated' : 'Deactivated';

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $status . ' Announcement: ' . $announcement->title,
            'model_type' => 'Announcement',
            'ip_address' => $request->ip()
        ]);

        return redirect()->back()->with('success', 'Announcement ' . strtolower($status) . ' successfully.');
    }
}
