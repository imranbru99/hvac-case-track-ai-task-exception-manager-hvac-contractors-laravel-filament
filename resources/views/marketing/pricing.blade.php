@extends('layouts.marketing')

@section('title', 'Pricing — HvacCaseTrack Pilot for HVAC Contractors')
@section('meta_description', 'HvacCaseTrack pilot pricing $299–$1,499/mo for HVAC contractor task & exception management with SLA automation.')

@section('content')
<section class="wrap section">
    <p class="eyebrow">Pricing hypothesis</p>
    <h1>Pilot $299–$1,499 / month</h1>
    <p class="lead">Sized for HVAC contractor owners and operations leads who need SLA discipline without enterprise FSM complexity.</p>

    <div class="grid grid-3" style="margin-top:1.5rem">
        <div class="card">
            <h3>Starter Pilot</h3>
            <p class="muted">$299/mo — message intake, AI assist, SLA tasks, audit log for a single branch.</p>
        </div>
        <div class="card">
            <h3>Ops Pilot</h3>
            <p class="muted">$799/mo — exceptions, escalations, technicians, job tickets, multi-user roles.</p>
        </div>
        <div class="card">
            <h3>Growth Pilot</h3>
            <p class="muted">$1,499/mo — integrations (Jobber / ServiceTitan / QuickBooks / Google Calendar), multi-location.</p>
        </div>
    </div>

    <div class="cta-row">
        <a class="btn btn-accent" href="{{ route('contact') }}">Talk to Imran</a>
        <a class="btn btn-ghost" href="{{ route('demo') }}">Run the demo</a>
    </div>
</section>
@endsection
