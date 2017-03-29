<?php

namespace Codice\Console\Commands;

use Codice\User;
use Illuminate\Console\Command;
use Lang;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codice:install {--username=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Codice from the commandline';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->stepMigrate();
        $this->output->newLine(2);

        $email    = $this->option('email')    ?: $this->ask('What is your e-mail address?');

        if (!($password = $this->option('password'))) {
            do {
                $password = $this->secret('Finally, what password would you like to use?');
            } while ($password != $this->secret('Retype it'));
        }

        $this->table(
            ['E-mail', 'Password'],
            [[$email, str_repeat('*', strlen($password))]]
        );

        if ($this->option('no-interaction')
            || $this->confirm('Do you confirm those are valid credentials and wish to continue?')) {
            $this->stepCreateUser($email, $password);
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

    protected function stepCreateUser($email, $password)
    {
        $options = User::$defaultOptions;
        $options['language'] = Lang::getLocale();

        $this->output->write('Creating new user.......');
        $user = new User;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->options = $options;
        $user->save();
        $this->output->writeln(' done');

        $this->output->write('Writing welcome note....');
        $this->output->write(' done');
        $this->output->newLine(2);
    }

    protected function stepFinish()
    {
        $this->info("Codice installation is now finished, go ahead and log in!");
    }
}
