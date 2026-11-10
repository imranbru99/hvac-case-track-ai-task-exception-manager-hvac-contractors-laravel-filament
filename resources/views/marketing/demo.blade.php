@extends('layouts.marketing')

@section('title', 'Live Demo Intake — HvacCaseTrack')

@section('content')
<section class="wrap section">
    <p class="eyebrow">Interactive demo</p>
    <h1>Submit a case message</h1>
    <p class="lead">Watch AI assist classify your message into an SLA task draft. Then log into Filament to approve, assign, and escalate.</p>

    @if(session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    <div class="grid grid-2" style="margin-top:1rem">
        <form class="card" method="post" action="{{ route('demo.intake') }}">
            @csrf
            <label>Name</label>
            <input name="from_name" value="{{ old('from_name') }}" required>
            <label>Email</label>
            <input type="email" name="from_email" value="{{ old('from_email') }}">
            <label>Phone</label>
            <input name="from_phone" value="{{ old('from_phone') }}">
            <label>Channel</label>
            <select name="channel">
                <option value="portal">Portal</option>
                <option value="email">Email</option>
                <option value="sms">SMS</option>
                <option value="phone">Phone</option>
                <option value="ops_note">Ops Note</option>
            </select>
            <label>Subject</label>
            <input name="subject" value="{{ old('subject', 'AC not cooling — need urgent callback') }}">
            <label>Message</label>
            <textarea name="body" rows="5" required>{{ old('body', 'Residential AC not cooling since this morning. Need emergency repair ASAP. Please escalate if tech cannot arrive today.') }}</textarea>
            <label><input type="checkbox" name="consent_email" value="1" checked> Email consent</label>
            <label><input type="checkbox" name="consent_sms" value="1"> SMS consent</label>
            <div class="cta-row">
                <button class="btn btn-accent" type="submit">Classify with AI</button>
            </div>
        </form>
        <div class="card">
            <h3>Demo logins</h3>
            <p class="muted">Password for all users: <strong>password</strong></p>
            <ul class="muted">
                <li>admin@hvaccasetrack.test (admin)</li>
                <li>owner@hvaccasetrack.test (owner)</li>
                <li>staff@hvaccasetrack.test (staff)</li>
                <li>tech@hvaccasetrack.test (technician)</li>
                <li>customer@hvaccasetrack.test (customer — no admin)</li>
            </ul>
            <a class="btn btn-primary" href="{{ url('/admin/login') }}">Go to /admin</a>
        </div>
    </div>
</section>
@endsection
