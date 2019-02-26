<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendBirthdayEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email users a birthday message and promo code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      // $users = User::whereMonth('birthdate', '=', date('m'))->whereDay('birthdate', '=', date('d'))->get();
        $users = User::all();

        foreach($users as $user) {

      // Create a unique 8 character promo code for user
      $new_promo_code = new PromoCode([
          'promo_code' => str_random(8),
      ]);

      $user->promo_code()->save($new_promo_code);

      // Send the email to user
      Mail::queue('emails.birthday', ['user' => $user], function ($mail) use ($user) {
          $mail->to($user['email'])
              ->from('you@company.com', 'Company')
              ->subject('Happy Birthday!');
      });

  }

  $this->info('Birthday messages sent successfully!');

    }
}
