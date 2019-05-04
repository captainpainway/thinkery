<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('user:create', function() {
    $username = $this->ask('What is your username?');
    $email = $this->ask('What is your email?');
    $password = $this->secret('What is your password?');
    DB::table('users')->insert(['username'=>$username,'email'=>$email,'password'=>Hash::make($password)]);
})->describe('Create a new user');

Artisan::command('user:updatePassword', function() {
    $username = $this->ask('What is your username?');
    $email = $this->ask('What is your email?');
    $password = $this->secret('What is your new password?');
    DB::table('users')->where('username', $username)->where('email', $email)->update(['password' => Hash::make($password)]);
})->describe('Update a user password');

Artisan::command('user:delete', function() {
    $username = $this->ask('What is your username?');
    $email = $this->ask('What is your email?');
    $password = $this->secret('What is your password?');
    $verify = $this->ask('Are you sure you want to delete this user? (yes/no)');
    if ($verify == 'yes' || $verify == 'Yes') {
        $pass = DB::table('users')->select('password')->where('username', $username)->where('email', $email)->first();
        if (Hash::check($password, $pass->password)) {
            DB::table('users')->where('username', $username)->where('email', $email)->delete();
            echo "User deleted\n";
        } else {
            echo "User not found\n";
        }
    } else {
        echo "Delete aborted.\n";
    }
})->describe('Delete a user');