<?php

namespace Database\Seeders;

use App\Models\JobTicket;
use App\Models\ServiceRequest;
use App\Models\Technician;
use App\Models\User;
use App\Services\CaseWorkflowService;
use App\Services\ModuleManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncFromConfig();

        $users = [
            ['name' => 'Admin Demo', 'email' => 'admin@hvaccasetrack.test', 'role' => 'admin', 'phone' => '555-0100'],
            ['name' => 'Owner Demo', 'email' => 'owner@hvaccasetrack.test', 'role' => 'owner', 'phone' => '555-0101'],
            ['name' => 'Staff Demo', 'email' => 'staff@hvaccasetrack.test', 'role' => 'staff', 'phone' => '555-0102'],
            ['name' => 'Tech Demo', 'email' => 'tech@hvaccasetrack.test', 'role' => 'technician', 'phone' => '555-0103'],
            ['name' => 'Customer Demo', 'email' => 'customer@hvaccasetrack.test', 'role' => 'customer', 'phone' => '555-0104'],
        ];

        $created = [];
        foreach ($users as $user) {
            $created[$user['role']] = User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'phone' => $user['phone'],
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $techUser = $created['technician'];
        $technician = Technician::query()->updateOrCreate(
            ['user_id' => $techUser->id],
            [
                'name' => 'Marcus Coolidge',
                'license_number' => 'HVAC-TX-44821',
                'specialties' => ['emergency_no_cool', 'dispatch', 'heat_pump'],
                'availability_status' => 'available',
                'service_area' => 'Dallas–Fort Worth',
                'phone' => '555-0103',
                'is_active' => true,
            ]
        );

        Technician::query()->updateOrCreate(
            ['license_number' => 'HVAC-TX-55210'],
            [
                'name' => 'Elena Furnace',
                'specialties' => ['furnace_heat', 'maintenance', 'safety_hold'],
                'availability_status' => 'on_job',
                'service_area' => 'Austin Metro',
                'phone' => '555-0118',
                'is_active' => true,
            ]
        );

        $workflow = app(CaseWorkflowService::class);
        $staff = $created['staff'];
        $owner = $created['owner'];

        if (\App\Models\CaseMessage::query()->count() === 0) {
            $samples = [
                [
                    'channel' => 'email',
                    'from_name' => 'Chris Martinez',
                    'from_email' => 'chris.m@example.com',
                    'from_phone' => '555-3001',
                    'subject' => 'AC not cooling — urgent callback',
                    'body' => 'Residential home in Plano — AC not cooling at all since this morning. Need emergency repair ASAP. Phone 555-3001. Still broken after yesterday visit — please escalate.',
                    'consent_email' => true,
                    'consent_sms' => true,
                ],
                [
                    'channel' => 'ops_note',
                    'direction' => 'internal',
                    'from_name' => 'Dispatch Desk',
                    'from_email' => 'ops@hvaccasetrack.test',
                    'from_phone' => '555-0102',
                    'subject' => 'Parts delay on compressor — job blocked',
                    'body' => 'Internal ops note: waiting on compressor part backorder for furnace heat callback. Truck down related? Open exception and escalate to owner if SLA slips today.',
                    'consent_email' => false,
                    'consent_sms' => false,
                ],
                [
                    'channel' => 'phone',
                    'from_name' => 'Dana Lee',
                    'from_email' => 'dana.lee@example.com',
                    'from_phone' => '555-3002',
                    'subject' => 'Gas smell near furnace — safety',
                    'body' => 'Urgent: furnace has no heat and we smell gas near the unit. Safety hold until licensed HVAC tech arrives.',
                    'consent_email' => true,
                    'consent_sms' => false,
                ],
            ];

            foreach ($samples as $i => $sample) {
                $message = $workflow->intake($sample, $staff);
                if ($i < 2) {
                    $task = $workflow->approve($message, $owner, $staff);
                    if ($i === 1) {
                        $task->update([
                            'sla_due_at' => now()->subHours(2),
                            'status' => 'in_progress',
                        ]);
                        $workflow->openException($task->fresh(), $staff, [
                            'type' => 'parts_delay',
                            'title' => 'Compressor backorder blocking SLA',
                            'details' => 'Part ETA unknown; customer waiting on heat.',
                            'escalate' => true,
                        ]);
                    }
                }
            }
        }

        $sr = ServiceRequest::query()->first();
        if ($sr) {
            JobTicket::query()->updateOrCreate(
                ['ticket_number' => 'JT-DEMO0001'],
                [
                    'service_request_id' => $sr->id,
                    'technician_id' => $technician->id,
                    'status' => 'dispatched',
                    'scheduled_start' => now()->addDay()->setTime(9, 0),
                    'scheduled_end' => now()->addDay()->setTime(12, 0),
                    'safety_checklist' => ['PPE on site', 'Gas leak protocol', 'Electrical disconnect locked'],
                ]
            );
        }
    }
}
