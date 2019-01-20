<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param \App\User $user
     * @return void
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => $this->getActivity($user)
        ]);
    }

    /**
     * Returns the activities of the given user
     *
     * @param \App\User $user
     * @return \Illuminate\Support\Collection
     */
    private function getActivity($user)
    {
        return $user->activities()->latest()->with('subject')->take(50)->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
