<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
        $users = User::has('donationRecords')->get();

        foreach ($users as $user) {
            $lastDonation = $user->donationRecords()->latest('donation_date')->first();
            $nextDate = $lastDonation->donation_date->addDays(90);
            $reminderDate = $nextDate->subDays(7);

            if (now()->isSameDay($reminderDate)) {
                // Envoyer notification
                $this->sendReminder($user);
            }
        }
    })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
  private function sendReminder(User $user)
{
    // Utilisez votre système de notification existant
    $firebaseService = new FirebaseService();
    $firebaseService->sendNotification(
        $user->fcm_token,
        'Rappel de don de sang',
        'Vous serez éligible pour un nouveau don dans 7 jours!'
    );
}
}
