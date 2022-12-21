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
                        'message' => '<p>Hello,</p><p>Welcome to {{SITE_TITLE}}. You can now get cashback for all your purchases through our app.</p><p>Regards</p><p>{{SITE_TITLE}} Team</p>',
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}},'
            ),
            array('title' => 'New ticket email to user',
                        'detail' => 'Sent to user when they open a new ticket',
                        'key' => 'user_new_ticket',
                        'subject' => 'Thank you for contacting our support team',
                        'message' => '<p>Hello {{NAME}},</p><p>Thank you for contacting our support team. A support ticket has been opened for you with ID {{TICKET_ID}}. You will be notified when a response is made by email.<p>',
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{TICKET_ID}}, {{CATEGORY}}'

            ),
            array('title' => 'New ticket email to admin',
                        'detail' => 'Sent to admin when a new ticket opened',
                        'key' => 'admin_new_ticket',
                        'subject' => 'New Ticket',
                        'message' => '<p>You have a new ticket pending.<p><p>TIcket ID: {{TICKET_ID}}</p><p>Name: {{NAME}}</p><p>Email: {{EMAI}}</p><p>Category: {{CATEGORY}} </p><p>Message: {{MESSAGE}} </p>',
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{TICKET_ID}}, {{CATEGORY}}'

            ),
            array('title' => 'New claim email to admin',
                        'detail' => 'Sent to admin when a new claim ticket opened',
                        'key' => 'admin_new_claim',
                        'subject' => 'New Claim Ticket',
                        'message' => '<p>You have a new claim ticket pending.<p><p>TIcket ID: {{TICKET_ID}}</p><p>Name: {{NAME}}</p><p>Email: {{EMAIL}}</p><p>Claim: {{CLAIMTYPE}}</p><p></p><p>Message: {{MESSAGE}} </p>',
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{TICKET_ID}},  {{CLAIMTYPE}}'

            ),
            array('title' => 'New claim email to user',
                        'detail' => 'Sent to user when they open a new claim ticket',
                        'key' => 'user_new_claim',
                        'subject' => 'Thank you for contacting our support team',
                        'message' => '<p>Hello {{NAME}},</p><p>Thank you for contacting our support team. A support ticket has been opened for you with ID {{TICKET_ID}}. You will be notified when a response is made by email.<p>',
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{TICKET_ID}}, {{CLAIMTYPE}}'

            ),
            array('title' => 'New contact form entry email to admin',
                        'detail' => 'Sent to admin to notify for new contact form entry',
                        'key' => 'admin_new_contact',
                        'subject' => 'New contact Form Entry: {{SUBJECT}}',
                        'message' => "<p>You have a new contact form entry.</p><p>Email: {{EMAIL}}</p><p>Name: {{NAME}}</p><p>Message: {{MESSAGE}} </p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{SUBJECT}}, {{TICKET_ID}}, {{CATEGORY}}'

            ),
            array('title' => 'New contact form entry email to user',
                        'detail' => 'Sent to user for contact form entry confirmation',
                        'key' => 'user_new_contact',
                        'subject' => 'Thank You for reaching out',
                        'message' => "<p>Thank you for reaching out. We'll be in touch as soon as possible.</p><p>Email: {{EMAIL}}</p><p>Name: {{NAME}}</p><p>Message: {{MESSAGE}} </p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{MESSAGE}},'

            ),
            array('title' => 'Cashout request email to user',
                        'detail' => 'Sent to user for cashout confirmation',
                        'key' => 'user_new_cashout_request',
                        'subject' => 'We have received a cashout request from you',
                        'message' => "<p>We have received a cashout request from you to withdraw {{AMOUNT}}. Please allow 4 working days for {{AMOUNT}} to reach to your account.</p><p>Payment Method: {{METHOD}}</p><p><b>If you have not requested a withdrawl please contact us ASAP.</b></p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}},{{AMOUNT}}, {{METHOD}}'

            ),
            array('title' => 'Cashout request email to admin',
                        'detail' => 'Sent to admin to notify for new cashout request',
                        'key' => 'admin_new_cashout_request',
                        'subject' => 'New cashout request',
                        'message' => "<p>You have received a new cashout request.</p> <p>Amount: {{AMOUNT}}.</p><p>Payment Method: {{METHOD}}</p><p>Name: {{NAME}}</p><p>Email: {{EMAIL}}</p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}},{{AMOUNT}}, {{METHOD}}'

            ),
            array('title' => 'Cashback tracked email to user',
                        'detail' => 'Sent to user when a new cashback is tracked',
                        'key' => 'user_new_cashback_tracked',
                        'subject' => 'Congrats! You have received a new cashback',
                        'message' => "<p>Congrats! You have received a new cashback.</p><p>Store: {{STORE}}</p><p>Amount: {{AMOUNT}}</p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{NAME}}, {{EMAIL}}, {{STORE}}, {{AMOUNT}}'

            ),

            array('title' => 'Referral link email',
                        'detail' => 'Sent to referred user',
                        'key'    => 'referral_link',
                        'subject' => 'Referral link',
                        'message' => "<p>Hello,</p><p>Join me at Cashback where you can earn money when you shop online.</p><p>Click my referral link below to sign up so you can browse, shop and earn while supporting Black-owned businesses with Cashback!</p>{{BUTTON}}<p>Regards</p><p>{{SITE_TITLE}} Team</p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}}, {{REFERRAL_LINK}} , {{BUTTON}}'

            ),

            array('title' => 'Email verification to user',
                        'detail' => 'Sent to new user to verify his/her account',
                        'key'    => 'email_verification',
                        'subject' => 'Email verification',
                        'message' => "<p>Hello,</p><p> You registered an account on Cashback, before being able to use your account you need to verify that this is your email address by clicking here:</p>{{BUTTON}}<p>Regards</p><p>{{SITE_TITLE}} Team</p>",
                        'keywords'=>'{{SITE_TITLE}}, {{SITE_URL}} , {{BUTTON}}'

            ),
        );
       
        foreach ($templates as $template) {
            EmailTemplate::create($template);
        }
        
    }
}
