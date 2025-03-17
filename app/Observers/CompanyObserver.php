<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\User;
use App\Notifications\CompanyRegistered;
use App\Notifications\CompanyStatusUpdated;
use Filament\Notifications\Notification;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        // Get all admin users
        $admins = User::where('role', 'admin')->get();

        // Send notification to all admins
        Notification::send($admins, new CompanyRegistered($company));
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        // Check if the status was changed 'Approved' or 'Rejected'

        if ($company->isDirty('status')) {
            // Get the company owner
            $recruiter = $company->user;

            // Send notification to the recruiter about status change
            $recruiter->notify(new CompanyStatusUpdated($company));
        }
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}
