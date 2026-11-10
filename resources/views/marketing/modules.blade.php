@extends('layouts.marketing')

@section('title', 'Modules — Toggle HvacCaseTrack features on or off')
@section('meta_description', 'Every HvacCaseTrack module with on/off status. HVAC contractors enable only the task, exception, escalation, and integration modules they need.')

@section('content')
<section class="wrap section">
    <p class="eyebrow">Modular platform</p>
    <h1>Run only the modules your shop needs</h1>
    <p class="lead">Core task/exception modules ship ON. Niche portals, attendance, PDF, and CRM integrations default OFF — flip them in Filament Module Settings.</p>

    <div class="grid grid-2" style="margin-top:1.5rem">
        @foreach($modules as $module)
            <div class="card">
                <span class="pill {{ $module['enabled'] ? '' : 'off' }}">{{ $module['enabled'] ? 'ON' : 'OFF' }}</span>
                <small class="muted" style="margin-left:.4rem">{{ $module['category'] }}</small>
                <h3 style="margin-top:.55rem">{{ $module['name'] }}</h3>
                <p class="muted">{{ $module['description'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
