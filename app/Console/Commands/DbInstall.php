<?php

namespace Codice\Console\Commands;

use Codice\User;
use Illuminate\Console\Command;
use Lang;

class DbInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:install {--username=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->stepMigrate();
        $this->output->newLine(2);

        $username = $this->option('username') ?: $this->ask('What will be your username?');
        $email    = $this->option('email')    ?: $this->ask('What will be your e-mail (used in login form)?');

        if(!($password = $this->option('password'))) {
            do {
                $password = $this->secret('Finally, what password would you like to use?');
            } while($password != $this->secret('Retype it'));
        }

        $this->table(
            ['Username', 'E-mail', 'Password'],
            [[$username, $email, str_repeat('*', strlen($password))]]
        );

        if ($this->option('no-interaction') || $this->confirm('Do you confirm those are valid credentials and wish to continue?')) {
            $this->stepCreateUser($username, $email, $password);
            $this->stepFinish();
        } else {
            $this->error('Installation cancelled');
        }
    }

    protected function stepMigrate()
    {
        $this->output->write('Migrating database......');
        $this->callSilent('migrate', ['--force' => true]);
        $this->output->write(' done');
    }

    protected function stepCreateUser($username, $email, $password)
    {
        $options = User::$defaultOptions;
        $options['language'] = Lang::getLocale();

        $this->output->write('Creating new user.......');
        $user = new User;
        $user->name = $username;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->options = $options;
        $user->save();
        $this->output->writeln(' done');

        $this->output->write('Writing welcome note....');
        $user->addWelcomeNote();
        $this->output->write(' done');
        $this->output->newLine(2);
    }

    protected function stepFinish()
    {
        $this->info("Codice installation is now finished, go ahead and log in!");
    }
}
