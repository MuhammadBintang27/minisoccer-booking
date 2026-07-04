<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemberRequest;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $member = Member::with(['user', 'paketLangganan' => fn ($q) => $q->latest()])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.member.index', compact('member'));
    }

    public function edit(Member $member): View
    {
        $member->load('user');

        return view('admin.member.edit', compact('member'));
    }

    public function update(MemberRequest $request, Member $member): RedirectResponse
    {
        $validated = $request->validated();

        $member->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        $member->update(['status' => $validated['status']]);

        return redirect()->route('admin.member.index')->with('status', 'Data member berhasil diperbarui.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        $member->user->delete();

        return redirect()->route('admin.member.index')->with('status', 'Member berhasil dihapus.');
    }
}
