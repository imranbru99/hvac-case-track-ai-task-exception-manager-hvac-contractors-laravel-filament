<?php

namespace App\Http\Controllers;

use App\Services\CaseWorkflowService;
use App\Services\ModuleManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function home(ModuleManager $modules): View
    {
        return view('marketing.home', [
            'modules' => $modules->all(),
        ]);
    }

    public function features(ModuleManager $modules): View
    {
        return view('marketing.features', [
            'modules' => $modules->all(),
        ]);
    }

    public function modules(ModuleManager $modules): View
    {
        return view('marketing.modules', [
            'modules' => $modules->all(),
        ]);
    }

    public function pricing(): View
    {
        return view('marketing.pricing');
    }

    public function workflow(): View
    {
        return view('marketing.workflow');
    }

    public function demo(): View
    {
        return view('marketing.demo');
    }

    public function contact(): View
    {
        return view('marketing.contact');
    }

    public function submitMessage(Request $request, CaseWorkflowService $workflow): RedirectResponse
    {
        $data = $request->validate([
            'from_name' => ['required', 'string', 'max:255'],
            'from_email' => ['nullable', 'email'],
            'from_phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'channel' => ['nullable', 'string', 'max:50'],
            'body' => ['required', 'string'],
            'consent_email' => ['nullable', 'boolean'],
            'consent_sms' => ['nullable', 'boolean'],
        ]);

        $data['channel'] = $data['channel'] ?? 'portal';
        $data['consent_email'] = $request->boolean('consent_email');
        $data['consent_sms'] = $request->boolean('consent_sms');

        $message = $workflow->intake($data);

        return redirect()
            ->route('demo')
            ->with('success', "Message {$message->reference} received and AI-classified as {$message->ai_category} (score {$message->ai_score}, SLA {$message->suggested_sla_hours}h).");
    }
}
