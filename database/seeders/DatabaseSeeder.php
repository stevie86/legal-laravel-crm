<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Client;
use App\Models\CounselingSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the UserSeeder to create the Admin and Counselor roles and users.
        $this->call([
            UserSeeder::class,
        ]);

        // Create a test user idempotently (won't fail on re-run)
        $user = User::firstOrCreate(
            ['email' => 'beraterin@example.com'],
            [
                'name' => 'Dr. Maria Müller',
                'password' => Hash::make('password'),
            ]
        );

        // Erstelle Test-Klienten
        $clients = [
            [
                'first_name' => 'Anna',
                'last_name' => 'Schmidt',
                'email' => 'anna.schmidt@email.com',
                'mobile' => '+49 170 1234567',
                'birth_date' => '1985-03-15',
                'gender' => 'female',
                'address' => 'Musterstraße 123',
                'postal_code' => '12345',
                'city' => 'Berlin',
                'status' => 'active',
            ],
            [
                'first_name' => 'Thomas',
                'last_name' => 'Weber',
                'email' => 'thomas.weber@email.com',
                'phone' => '+49 30 9876543',
                'birth_date' => '1978-11-22',
                'gender' => 'male',
                'address' => 'Beispielweg 456',
                'postal_code' => '54321',
                'city' => 'Hamburg',
                'status' => 'active',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@email.com',
                'mobile' => '+49 175 5555555',
                'birth_date' => '1992-07-08',
                'gender' => 'female',
                'address' => 'Teststraße 789',
                'postal_code' => '67890',
                'city' => 'München',
                'status' => 'active',
            ],
        ];

        foreach ($clients as $clientData) {
            $client = Client::create($clientData);

            // Erstelle einige Beratungssitzungen für jeden Klienten
            $sessions = [
                [
                    'client_id' => $client->id,
                    'user_id' => $user->id,
                    'title' => 'Erstberatung',
                    'description' => 'Erstes Kennenlernen und Anamnese',
                    'scheduled_at' => Carbon::now()->addDays(rand(1, 7))->setHour(rand(9, 17))->setMinute(0),
                    'duration_minutes' => 60,
                    'status' => 'scheduled',
                    'session_type' => 'individual',
                    'location' => 'Praxis Raum 1',
                    'fee' => 80.00,
                ],
                [
                    'client_id' => $client->id,
                    'user_id' => $user->id,
                    'title' => 'Folgeberatung',
                    'description' => 'Vertiefung der Themen',
                    'scheduled_at' => Carbon::now()->addDays(rand(8, 14))->setHour(rand(9, 17))->setMinute(0),
                    'duration_minutes' => 50,
                    'status' => 'scheduled',
                    'session_type' => 'individual',
                    'location' => 'Praxis Raum 1',
                    'fee' => 70.00,
                ],
            ];

            foreach ($sessions as $sessionData) {
                $session = CounselingSession::create($sessionData);

                // Erstelle entsprechende Kalendereinträge
                CalendarEvent::create([
                    'user_id' => $user->id,
                    'client_id' => $client->id,
                    'counseling_session_id' => $session->id,
                    'title' => $session->title.' - '.$client->full_name,
                    'description' => $session->description,
                    'start_time' => $session->scheduled_at,
                    'end_time' => $session->scheduled_at->copy()->addMinutes($session->duration_minutes),
                    'event_type' => 'session',
                    'location' => $session->location,
                    'color' => '#3788d8',
                ]);
            }
        }

        // Erstelle einige zusätzliche Kalendereinträge
        $additionalEvents = [
            [
                'user_id' => $user->id,
                'title' => 'Team-Meeting',
                'description' => 'Wöchentliches Team-Meeting',
                'start_time' => Carbon::now()->addDays(3)->setHour(14)->setMinute(0),
                'end_time' => Carbon::now()->addDays(3)->setHour(15)->setMinute(30),
                'event_type' => 'other',
                'color' => '#28a745',
            ],
            [
                'user_id' => $user->id,
                'title' => 'Fortbildung',
                'description' => 'Fortbildung zu neuen Therapiemethoden',
                'start_time' => Carbon::now()->addDays(5)->setHour(9)->setMinute(0),
                'end_time' => Carbon::now()->addDays(5)->setHour(17)->setMinute(0),
                'event_type' => 'other',
                'color' => '#ffc107',
            ],
        ];

        foreach ($additionalEvents as $eventData) {
            CalendarEvent::create($eventData);
        }
    }
}
