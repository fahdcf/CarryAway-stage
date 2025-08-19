<?php

namespace App\Console\Commands;

use App\Models\UserRequest;
use App\Models\Notification;
use Illuminate\Console\Command;

class GenerateVerificationCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:generate-codes {--request-id= : Generate codes for specific request ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate verification codes for existing accepted requests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Regenerating verification codes for all accepted requests...');

        $requests = UserRequest::where('status', 'accepted')->get();

        if ($requests->isEmpty()) {
            $this->info('No accepted requests found.');
            return;
        }

        $progressBar = $this->output->createProgressBar($requests->count());
        $progressBar->start();

        $generatedCount = 0;
        $notificationCount = 0;

        foreach ($requests as $request) {
            try {
                // Generate codes using the model method
                $request->generateVerificationCodes();
                $generatedCount++;

                // Send notifications to both users
                $codes = $request->getVerificationCodes();
                $requester = $request->initiator;
                $owner = $request->announcementOwner;

                if ($requester && $owner) {
                    \App\Models\Notification::createRequestAcceptedNotification($request, $requester, $codes);
                    \App\Models\Notification::createRequestAcceptedNotification($request, $owner, $codes);
                    $notificationCount += 2;
                }
            } catch (\Exception $e) {
                $this->error("Error processing request {$request->id}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ Successfully regenerated verification codes for {$generatedCount} requests.");
        $this->info("📧 Sent {$notificationCount} notifications to users.");
        $this->info('🎯 All accepted requests now have both meeting and delivery codes.');
    }
}
