<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $templates  =array(

            array('title' => 'User Welcome',
                        'detail' => 'Sent to user after account registration',
                        'key' => 'user_welcome',
                        'subject' => 'Welcome',
                        'message' => '<p>Hello %NAME%,</p><p>Welcome to%SITE_TITLE%. You can now get cashback for all your purchases through our app.</p><p>Regards</p><p>%SITE_TITLE% Team</p>',
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%,'
            ),
            array('title' => 'New ticket email to user',
                        'detail' => 'Sent to user when they open a new ticket',
                        'key' => 'user_new_ticket',
                        'subject' => 'Thank you for contacting our support team',
                        'message' => '<p>Hello %NAME%,</p><p>Thank you for contacting our support team. A support ticket has been opened for you with ID %TICKET_ID%. You will be notified when a response is made by email.<p>',
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%, %TICKET_ID%, %CATEGORY%'

            ),
            array('title' => 'New ticket email to admin',
                        'detail' => 'Sent to admin when a new ticket opened',
                        'key' => 'admin_new_ticket',
                        'subject' => 'New Ticket',
                        'message' => '<p>You have a new ticket pending.<p><p>TIcket ID: %TICKET_ID%</p><p>Name: %NAME%</p><p>Email: %EMAIL%</p><p>Category: %CATEGORY% </p><p>Message: %MESSAGE% </p>',
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%, %TICKET_ID%, %CATEGORY%'

            ),
            array('title' => 'New claim email to admin',
                        'detail' => 'Sent to admin when a new claim ticket opened',
                        'key' => 'admin_new_claim',
                        'subject' => 'New Claim Ticket',
                        'message' => '<p>You have a new claim ticket pending.<p><p>TIcket ID: %TICKET_ID%</p><p>Name: %NAME%</p><p>Email: %EMAIL%</p><p>Claim: %CLAIMTYPE%</p><p></p><p>Message: %MESSAGE% </p>',
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%, %TICKET_ID%,  %CLAIMTYPE%'

            ),
            array('title' => 'New claim email to user',
                        'detail' => 'Sent to user when they open a new claim ticket',
                        'key' => 'user_new_claim',
                        'subject' => 'Thank you for contacting our support team',
                        'message' => '<p>Hello %NAME%,</p><p>Thank you for contacting our support team. A support ticket has been opened for you with ID %TICKET_ID%. You will be notified when a response is made by email.<p>',
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%, %TICKET_ID%, %CLAIMTYPE%'

            ),
            array('title' => 'New contact form entry email to admin',
                        'detail' => 'Sent to admin to notify for new contact form entry',
                        'key' => 'admin_new_contact',
                        'subject' => 'New contact Form Entry: %SUBJECT%',
                        'message' => "<p>You have a new contact form entry.</p><p>Email: %EMAIL%</p><p>Name: %NAME%</p><p>Message: %MESSAGE% </p>",
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%, %SUBJECT%, %TICKET_ID%, %CATEGORY%'

            ),
            array('title' => 'New contact form entry email to user',
                        'detail' => 'Sent to user for contact form entry confirmation',
                        'key' => 'user_new_contact',
                        'subject' => 'Thank You for reaching out',
                        'message' => "<p>Thank you for reaching out. We'll be in touch as soon as possible.</p><p>Email: %EMAIL%</p><p>Name: %NAME%</p><p>Message: %MESSAGE% </p>",
                        'keywords'=>'%SITE_TITLE%, %SITE_URL%, %NAME%, %EMAIL%, %MESSAGE%,'

            ),
        );

        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
        
    }
}
