<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'is_teacher' => ['sometimes', 'accepted'],
        ])->validate();

        $isTeacher = isset($input['is_teacher']) && $input['is_teacher'];

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => $isTeacher ? 'teacher' : 'student',
            'teacher_status' => $isTeacher ? 'pending' : 'approved',
            'teacher_rejection_reason' => null,
        ]);
    }
}
