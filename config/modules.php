<?php

return [
    /*
    |--------------------------------------------------------------------------
    | HvacCaseTrack Feature Modules
    |--------------------------------------------------------------------------
    | Toggle modules per HVAC contractor needs. Core task/exception modules
    | default ON; niche / integration modules default OFF.
    */

    'definitions' => [
        'message_intake' => [
            'name' => 'Message Intake',
            'description' => 'Capture customer and operations messages into a unified case queue.',
            'category' => 'core',
            'enabled' => true,
        ],
        'ai_task_assist' => [
            'name' => 'AI Task Assist',
            'description' => 'Classify messages and draft SLA tasks with priority, owner hints, and due windows.',
            'category' => 'ai',
            'enabled' => true,
        ],
        'human_approval_workflow' => [
            'name' => 'Human Approval Workflow',
            'description' => 'Require ops approval before AI-drafted tasks become live SLA work.',
            'category' => 'core',
            'enabled' => true,
        ],
        'sla_tasks' => [
            'name' => 'SLA Tasks',
            'description' => 'Assign tasks with response and resolution SLAs for HVAC operations.',
            'category' => 'core',
            'enabled' => true,
        ],
        'exception_manager' => [
            'name' => 'Exception Manager',
            'description' => 'Open exceptions for SLA breach, blocked jobs, parts delays, and safety holds.',
            'category' => 'core',
            'enabled' => true,
        ],
        'escalation_engine' => [
            'name' => 'Escalation Engine',
            'description' => 'Escalate overdue tasks and open exceptions to owners and on-call leads.',
            'category' => 'core',
            'enabled' => true,
        ],
        'service_requests' => [
            'name' => 'Service Requests',
            'description' => 'Link tasks and exceptions to HVAC service requests.',
            'category' => 'operations',
            'enabled' => true,
        ],
        'field_technicians' => [
            'name' => 'Field Technicians',
            'description' => 'Manage licensed HVAC technicians, specialties, and availability.',
            'category' => 'operations',
            'enabled' => true,
        ],
        'job_tickets' => [
            'name' => 'Job Tickets',
            'description' => 'Dispatch job tickets with safety checklists to technicians.',
            'category' => 'operations',
            'enabled' => true,
        ],
        'audit_log' => [
            'name' => 'Audit Log',
            'description' => 'Immutable trail of intake, approve, assign, escalate, and resolve actions.',
            'category' => 'compliance',
            'enabled' => true,
        ],
        'messaging_consent' => [
            'name' => 'Messaging Consent',
            'description' => 'Track SMS/email consent for TCPA-aware customer updates.',
            'category' => 'compliance',
            'enabled' => true,
        ],
        'slug_engine' => [
            'name' => 'Universal Slug Engine',
            'description' => 'Generate URL-safe slugs for customers and public case pages (local laravel-universal-slug equivalent).',
            'category' => 'platform',
            'enabled' => true,
        ],
        'customer_portal' => [
            'name' => 'Customer Portal',
            'description' => 'Let homeowners check case/task status (optional niche module).',
            'category' => 'portal',
            'enabled' => false,
        ],
        'attendance_hub' => [
            'name' => 'Attendance Hub',
            'description' => 'Technician clock-in / attendance machine integration (local laravel-attendance-hub equivalent).',
            'category' => 'hr',
            'enabled' => false,
        ],
        'unicode_pdf_exports' => [
            'name' => 'Unicode PDF Exports',
            'description' => 'Export SLA and exception reports as PDF (local laravel-unicode-pdf equivalent).',
            'category' => 'documents',
            'enabled' => false,
        ],
        'ai_catalog_scrape' => [
            'name' => 'AI Catalog / Parts Enrichment',
            'description' => 'Enrich exception notes from online catalog sources (local laravel-ai-catalog equivalent).',
            'category' => 'ai',
            'enabled' => false,
        ],
        'ai_hub' => [
            'name' => 'AI Hub Provider Switch',
            'description' => 'Route task assist through pluggable AI providers (local laravel-ai-hub equivalent).',
            'category' => 'ai',
            'enabled' => true,
        ],
        'drutogoti_speed' => [
            'name' => 'Drutogoti Speed Layer',
            'description' => 'Caching and queue acceleration helpers (local drutogoti equivalent).',
            'category' => 'platform',
            'enabled' => true,
        ],
        'admin_panel_extras' => [
            'name' => 'Admin Panel Extras',
            'description' => 'Extended Filament admin dashboard widgets (local laravel-admin-panel equivalent).',
            'category' => 'platform',
            'enabled' => true,
        ],
        'integrations_jobber' => [
            'name' => 'Jobber Integration',
            'description' => 'Sync tasks and job tickets to Jobber.',
            'category' => 'integrations',
            'enabled' => false,
        ],
        'integrations_servicetitan' => [
            'name' => 'ServiceTitan Integration',
            'description' => 'Push tasks and exceptions into ServiceTitan.',
            'category' => 'integrations',
            'enabled' => false,
        ],
        'integrations_quickbooks' => [
            'name' => 'QuickBooks Integration',
            'description' => 'Link resolved billable exceptions to QuickBooks.',
            'category' => 'integrations',
            'enabled' => false,
        ],
        'integrations_google_calendar' => [
            'name' => 'Google Calendar Integration',
            'description' => 'Block technician calendars from SLA due windows.',
            'category' => 'integrations',
            'enabled' => false,
        ],
        'multi_location' => [
            'name' => 'Multi-Location',
            'description' => 'Route cases by branch / service territory.',
            'category' => 'operations',
            'enabled' => false,
        ],
    ],
];
