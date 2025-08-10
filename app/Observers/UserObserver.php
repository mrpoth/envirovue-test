<?php

namespace App\Observers;

use App\Enums\DetailKey;
use App\Enums\UserTitles;
use App\Models\Detail;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->handleSavedUser($user);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->handleSavedUser($user);
    }

    private function handleSavedUser(User $user)
    {
        $genderMap = [
            UserTitles::Mr->value => 'Male',
            UserTitles::Mrs->value => 'Female',
            UserTitles::Ms->value => 'Female',
        ];

        $gender = $genderMap[$user->prefixname];

        $details = [
            ['key' => DetailKey::FullName->value, 'value' => $user->getAttribute('full_name')],
            ['key' => DetailKey::MiddleInitial->value, 'value' => $user->getAttribute('middle_initial')],
            ['key' => DetailKey::Gender->value, 'value' => $gender],
        ];

        foreach ($details as $detail) {
            Detail::updateOrCreate(
                ['user_id' => $user->id, 'key' => $detail['key']],
                ['value' => $detail['value']]
            );
        }
    }
}
