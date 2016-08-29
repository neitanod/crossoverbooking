<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\EventRepository;

class SendEmailReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crossover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email report about visitor';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $alerts = EventRepository::getRecentEvents();
        foreach($alerts as $alert){
            if(!empty($alert->admin_name)){
                $this->comment('Alert sent to '.$alert->admin_name.' ('.$alert->name.')');
                //TODO: Send emails here;
                //
                // $mailer = $this->getMailer();
                // $this->message = Swift_Message::newInstance();
                // $this->message
                //           ->setFrom(array('system@crossover.ip1.cc' => 'system@crossover.ip1.cc'))
                //           ->setTo(array($alert->admin_name => $alert->admin_email ))
                //           ->setSubject('Your event has concluded')
                //           ->setBody($this->getPartial('body_txt',  $data))
                //           ->addPart($this->getPartial('body_html', $data), "text/html");
                //
                // $result = $mailer->send($this->message);
            }
        }

        $this->comment(PHP_EOL.'Emails sent'.PHP_EOL);
    }
}
