@extends('layouts.marketing')

@section('title', 'Workflow — HVAC Task & Exception Manager MVP')
@section('meta_description', 'HvacCaseTrack MVP workflow: message intake → AI draft/action → human approval → SLA task → exception escalation → audit log.')

@section('content')
<section class="wrap section">
    <p class="eyebrow">Operations workflow</p>
    <h1>Intake → draft → approve → escalate</h1>
    <p class="lead">Designed for HVAC contractors who must turn messy customer and ops messages into accountable SLA work.</p>

    <div class="grid" style="margin-top:1.5rem; gap:1rem">
        <div class="card">
            <h3>1. Intake trigger</h3>
            <p class="muted">A homeowner email, SMS, phone note, portal form, or internal ops note lands in the case desk.</p>
        </div>
        <div class="card">
            <h3>2. AI draft / action</h3>
            <p class="muted">HvacCaseTrack classifies category and priority, suggests an SLA window, and drafts a task title/description.</p>
        </div>
        <div class="card">
            <h3>3. Human approval</h3>
            <p class="muted">Staff or owner approves the draft. A live SLA task is assigned; optional service request is created.</p>
        </div>
        <div class="card">
            <h3>4. Exception & escalation</h3>
            <p class="muted">If the SLA slips or work is blocked (parts, safety), open an exception and escalate to the ops lead — with full audit trail.</p>
        </div>
    </div>

    <div class="cta-row">
        <a class="btn btn-primary" href="{{ url('/admin') }}">Open admin to run the loop</a>
    </div>
</section>
@endsection
