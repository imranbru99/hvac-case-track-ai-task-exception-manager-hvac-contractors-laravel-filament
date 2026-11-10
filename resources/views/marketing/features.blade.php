@extends('layouts.marketing')

@section('title', 'Features — HvacCaseTrack HVAC Task & Exception Manager')
@section('meta_description', 'Full feature list for HvacCaseTrack: AI task assist, SLA tasks, exceptions, escalations, service requests, technicians, job tickets, audit log, and modular integrations.')

@section('content')
<section class="wrap section">
    <p class="eyebrow">Product features</p>
    <h1>Everything an HVAC ops desk needs</h1>
    <p class="lead">From message intake to SLA resolution — with AI drafts, human approval, exceptions, escalations, and field dispatch.</p>

    <div class="grid grid-2" style="margin-top:1.5rem">
        <div class="card">
            <h3>Message intake desk</h3>
            <p class="muted">Capture inbound customer messages and internal ops notes across email, SMS, phone, portal, Jobber, and ServiceTitan channels.</p>
        </div>
        <div class="card">
            <h3>AI task assist</h3>
            <p class="muted">Classify urgency (no-cool, furnace, safety hold, parts delay), suggest titles, categories, priorities, and SLA hours.</p>
        </div>
        <div class="card">
            <h3>Human approval workflow</h3>
            <p class="muted">Staff/owners approve AI drafts before live SLA tasks are assigned — MVP: intake → draft → approve → audit.</p>
        </div>
        <div class="card">
            <h3>SLA task board</h3>
            <p class="muted">Assigned tasks with due windows, breach flags, first-response and resolution timestamps.</p>
        </div>
        <div class="card">
            <h3>Exception manager</h3>
            <p class="muted">Open exceptions for SLA breach, parts delay, safety hold, ops block, callback, and billing issues.</p>
        </div>
        <div class="card">
            <h3>Escalation engine</h3>
            <p class="muted">Multi-level escalations to owners and on-call leads with acknowledge/resolve states.</p>
        </div>
        <div class="card">
            <h3>Field operations</h3>
            <p class="muted">Service requests, licensed technicians, and job tickets with safety checklists.</p>
        </div>
        <div class="card">
            <h3>Compliance & audit</h3>
            <p class="muted">Messaging consent flags plus immutable audit log for classify, approve, escalate, and resolve.</p>
        </div>
    </div>

    <div class="cta-row">
        <a class="btn btn-accent" href="{{ route('demo') }}">Try demo intake</a>
        <a class="btn btn-ghost" href="{{ route('modules') }}">Module toggles</a>
    </div>
</section>
@endsection
