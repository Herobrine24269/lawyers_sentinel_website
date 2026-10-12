<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;
use App\Models\LawyerProfile;
use App\Models\LawyerSchedule;
use App\Models\Appointment;
use App\Models\HomepageContent;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Homepage Content
        HomepageContent::create([
            'hero_title' => 'Find & Book Top Verified Legal Experts',
            'hero_subtitle' => 'Connect directly with certified attorneys across Criminal Law, Divorce & Family, Affidavit & Documentation, and Civil Litigation. Schedule your consultation slot in minutes.',
            'announcement_banner' => '✨ Welcome to Juris Sentinel - Schedule online or in-person legal consultations with top attorneys.',
            'total_lawyers_count' => 180,
            'total_cases_count' => 5400,
            'satisfaction_rate' => '99.2%',
        ]);

        // 2. Seed Admin User
        User::create([
            'name' => 'Juris System Administrator',
            'email' => 'admin@juris.com',
            'password' => Hash::make('password'),
            'phone' => '+1 (800) 555-0199',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // 3. Seed Services (Required: Criminal, Divorce, Affidavit, Civil)
        $criminal = Service::create([
            'name' => 'Criminal Law',
            'slug' => 'criminal',
            'description' => 'Defense representation for felony, misdemeanor, DUI, bail hearings, and criminal trials.',
            'icon' => 'shield-exclamation',
            'is_active' => true,
        ]);

        $divorce = Service::create([
            'name' => 'Divorce & Family Law',
            'slug' => 'divorce',
            'description' => 'Expert counsel for marital dissolution, child custody, alimony, and property settlement.',
            'icon' => 'users',
            'is_active' => true,
        ]);

        $affidavit = Service::create([
            'name' => 'Affidavit & Documentation',
            'slug' => 'affidavit',
            'description' => 'Notarization, legal affidavits, property contracts, power of attorney, and sworn statements.',
            'icon' => 'document-text',
            'is_active' => true,
        ]);

        $civil = Service::create([
            'name' => 'Civil Litigation',
            'slug' => 'civil',
            'description' => 'Dispute resolution, breach of contract, property damage, debt recovery, and tort litigation.',
            'icon' => 'scale',
            'is_active' => true,
        ]);

        $corporate = Service::create([
            'name' => 'Corporate & Business Law',
            'slug' => 'corporate',
            'description' => 'Company registration, commercial contracts, regulatory compliance, and M&A advisory.',
            'icon' => 'briefcase',
            'is_active' => true,
        ]);

        // 4. Seed Demo Lawyers
        $lawyerData = [
            [
                'name' => 'Attorney Sarah Jenkins',
                'email' => 'sarah.jenkins@law.com',
                'phone' => '+1 (555) 234-5678',
                'location' => 'New York, NY',
                'meeting_place' => 'Office, Virtual Video Call',
                'specializations' => [$criminal->id, $civil->id],
                'experience_years' => 12,
                'license_number' => 'NY-BAR-88912',
                'hourly_fee' => 220.00,
                'bio' => 'Senior Defense Counsel with over 12 years of trial experience in high-stakes criminal defense and complex civil litigation. Dedicated to fighting fiercely for client constitutional rights.',
                'rating' => 4.9,
                'total_reviews' => 48,
                'is_featured' => true,
            ],
            [
                'name' => 'Attorney Michael Vance',
                'email' => 'michael.vance@law.com',
                'phone' => '+1 (555) 876-5432',
                'location' => 'Los Angeles, CA',
                'meeting_place' => 'In-Person Office, Virtual Call',
                'specializations' => [$divorce->id],
                'experience_years' => 15,
                'license_number' => 'CA-BAR-44109',
                'hourly_fee' => 250.00,
                'bio' => 'Compassionate family law specialist focusing on fair divorce settlements, child custody negotiations, and asset division. Priority is minimizing emotional stress while securing client goals.',
                'rating' => 4.8,
                'total_reviews' => 62,
                'is_featured' => true,
            ],
            [
                'name' => 'Attorney Elena Rostova',
                'email' => 'elena.rostova@law.com',
                'phone' => '+1 (555) 345-6789',
                'location' => 'Chicago, IL',
                'meeting_place' => 'Virtual Video Call, Client Premises',
                'specializations' => [$affidavit->id, $corporate->id],
                'experience_years' => 8,
                'license_number' => 'IL-BAR-77231',
                'hourly_fee' => 175.00,
                'bio' => 'Specialist in legal drafting, sworn affidavits, property contracts, and corporate documentation. Efficient turnarounds with airtight legal compliance guaranteed.',
                'rating' => 5.0,
                'total_reviews' => 31,
                'is_featured' => true,
            ],
            [
                'name' => 'Attorney David Sterling',
                'email' => 'david.sterling@law.com',
                'phone' => '+1 (555) 901-2345',
                'location' => 'Houston, TX',
                'meeting_place' => 'In-Person Office',
                'specializations' => [$civil->id, $criminal->id],
                'experience_years' => 10,
                'license_number' => 'TX-BAR-99014',
                'hourly_fee' => 195.00,
                'bio' => 'Trial attorney focused on civil disputes, commercial litigation, and misdemeanor criminal defense. Providing strategic, practical legal solutions tailored to individual needs.',
                'rating' => 4.7,
                'total_reviews' => 29,
                'is_featured' => false,
            ],
        ];

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        foreach ($lawyerData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'phone' => $data['phone'],
                'role' => 'lawyer',
                'status' => 'active',
            ]);

            LawyerProfile::create([
                'user_id' => $user->id,
                'location' => $data['location'],
                'meeting_place' => $data['meeting_place'],
                'specializations' => $data['specializations'],
                'experience_years' => $data['experience_years'],
                'license_number' => $data['license_number'],
                'hourly_fee' => $data['hourly_fee'],
                'bio' => $data['bio'],
                'rating' => $data['rating'],
                'total_reviews' => $data['total_reviews'],
                'is_featured' => $data['is_featured'],
                'is_approved' => true,
            ]);

            // Create availability schedule
            foreach ($daysOfWeek as $day) {
                LawyerSchedule::create([
                    'lawyer_id' => $user->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'slot_duration_minutes' => 45,
                    'is_available' => true,
                ]);
            }
        }

        // 5. Seed Customer User
        $customer = User::create([
            'name' => 'John Doe',
            'email' => 'customer@juris.com',
            'password' => Hash::make('password'),
            'phone' => '+1 (555) 777-8899',
            'role' => 'customer',
            'status' => 'active',
        ]);

        // 6. Seed Initial Appointments
        $firstLawyer = User::where('role', 'lawyer')->first();
        Appointment::create([
            'appointment_number' => 'APT-2026-001',
            'customer_id' => $customer->id,
            'lawyer_id' => $firstLawyer->id,
            'service_id' => $criminal->id,
            'appointment_date' => date('Y-m-d', strtotime('+2 days')),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'meeting_place' => 'Office',
            'notes' => 'Preliminary consultation regarding contractual disagreement and defense strategy.',
            'status' => 'confirmed',
        ]);

        Appointment::create([
            'appointment_number' => 'APT-2026-002',
            'customer_id' => $customer->id,
            'lawyer_id' => $firstLawyer->id,
            'service_id' => $civil->id,
            'appointment_date' => date('Y-m-d', strtotime('+5 days')),
            'start_time' => '14:00:00',
            'end_time' => '14:45:00',
            'meeting_place' => 'Virtual Video Call',
            'notes' => 'Review of property title affidavit draft.',
            'status' => 'pending',
        ]);
    }
}
