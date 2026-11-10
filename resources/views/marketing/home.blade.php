@extends('layouts.marketing')

@section('title', 'HvacCaseTrack — AI Task & Exception Manager for HVAC contractors')
@section('meta_description', 'Turn customer and operations messages into assigned SLA tasks with escalation. Operate on service requests, field technicians, and job tickets for HVAC contractors.')

@section('content')
<section class="wrap hero">
    <div>
        <p class="eyebrow">HVAC contractors · Operations</p>
        <h1>HvacCaseTrack</h1>
        <p class="lead">Turn customer and operations messages into assigned tasks with SLAs and escalation — then operate on service requests, field technicians, and job tickets with AI assist and human approval.</p>
        <div class="cta-row">
            <a class="btn btn-accent" href="{{ route('demo') }}">Try live intake</a>
            <a class="btn btn-ghost" href="{{ url('/admin') }}">Open Filament admin</a>
            <a class="btn btn-ghost" href="{{ route('features') }}">Explore all features</a>
        </div>
    </div>
    <div class="panel">
        <small>Live ops snapshot</small>
        <div class="score">99</div>
        <p style="margin:0 0 1rem; opacity:.9">Opportunity score · Task & Exception Manager MVP</p>
        <div class="grid" style="gap:.55rem">
            <div>1) Intake trigger → customer / ops message</div>
            <div>2) AI draft task + SLA window</div>
            <div>3) Human approval gate</div>
            <div>4) Escalate exceptions + audit log</div>
        </div>
    </div>
</section>

<section class="wrap section">
    <h2>Built for HVAC contractor ops leads</h2>
    <p class="muted" style="max-width:46rem">Stop losing callbacks, parts delays, and safety holds in inboxes. HvacCaseTrack turns every message into an SLA-backed task, opens exceptions when work stalls, and escalates to owners before customers churn.</p>
    <div class="grid grid-3" style="margin-top:1.25rem">
        <div class="card">
            <h3>Message → task</h3>
            <p class="muted">Email, SMS, phone, portal, and ops notes become assignable SLA work.</p>
        </div>
        <div class="card">
            <h3>Exception & escalate</h3>
            <p class="muted">SLA breaches, parts delays, and safety holds escalate to the right owner.</p>
        </div>
        <div class="card">
            <h3>Modular SaaS</h3>
            <p class="muted">Toggle technicians, Jobber/ServiceTitan hooks, attendance, and PDF exports per shop.</p>
        </div>
    </div>
</section>

<section class="wrap section">
    <h2>Core modules enabled for demo</h2>
    <div class="grid grid-2">
        @foreach(collect($modules)->where('enabled', true)->take(8) as $module)
            <div class="card">
                <span class="pill">ON</span>
                <h3 style="margin-top:.55rem">{{ $module['name'] }}</h3>
                <p class="muted">{{ $module['description'] }}</p>
            </div>
        @endforeach
    </div>
    <div class="cta-row">
        <a class="btn btn-primary" href="{{ route('modules') }}">View every module (on & off)</a>
    </div>
</section>
@endsection
