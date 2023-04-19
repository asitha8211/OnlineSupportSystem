<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class CreateSupportAgent extends Command
{
    protected $name = 'online_support_system:support_agent';

    public function __construct()
    {
        parent::__construct();
    }

    protected function getOptions(): array
    {
        return [
            ['create', null, InputOption::VALUE_NONE, 'Create a support agent user', null],
        ];
    }

    public function fire()
    {
        return $this->handle();
    }

    public function handle()
    {
        // Get or create user
        $user = $this->getUser(
            $this->option('create')
        );

        // the user not returned
        if (!$user) {
            exit;
        }

        $this->info('The user now has support agent access to your site.');
    }

    protected function getArguments(): array
    {
        return [
            ['email', InputOption::VALUE_REQUIRED, 'The email of the user.', null],
        ];
    }

    protected function getUser($create = false)
    {
        $email = $this->argument('email');
        $model = Auth::guard()->getProvider()->getModel();
        $user = Str::start($model, '\\');

        if ($create) {
            $name = $this->ask('Enter the support agent name');
            $password = $this->secret('Enter support agent password');
            $confirmPassword = $this->secret('Confirm Password');

            // Ask for email if there wasn't set one
            if (!$email) {
                $email = $this->ask('Enter the Enter the support agent email');
            }

            // check if user with given email exists
            if ($user::where('email', $email)->exists()) {
                $this->info("Can't create support agent. User with the email " . $email . ' exists already.');

                return;

            }

            // Passwords don't match
            if ($password != $confirmPassword) {
                $this->info("Passwords don't match");

                return;

            }

            $this->info('Creating support agent account');

            $supportAgent = call_user_func($user . '::forceCreate', [
                'name'     => $name,
                'role_id'     => $this->getSupportAgentRoleId(),
                'email'    => $email,
                'password' => Hash::make($password),
            ]);

            return $supportAgent;
        }

        return call_user_func($user . '::where', 'email', $email)->firstOrFail();
    }

    private function getSupportAgentRoleId()
    {
        return Role::where('name', Role::SUPPORT_AGENT)->value('id');
    }
}
