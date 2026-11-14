<?php

namespace Tests\Feature;

use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    public function test_mailtrap_uses_sandbox_smtp_defaults(): void
    {
        $mailer = config('mail.mailers.mailtrap');

        $this->assertSame('smtp', $mailer['transport']);
        $this->assertSame('sandbox.smtp.mailtrap.io', $mailer['host']);
        $this->assertSame(2525, $mailer['port']);
    }

    public function test_mail_to_use_overrides_the_legacy_mail_mailer(): void
    {
        $hadEnv = array_key_exists('MAIL_TO_USE', $_ENV);
        $previousEnv = $_ENV['MAIL_TO_USE'] ?? null;
        $hadServer = array_key_exists('MAIL_TO_USE', $_SERVER);
        $previousServer = $_SERVER['MAIL_TO_USE'] ?? null;
        $previousProcess = getenv('MAIL_TO_USE');

        putenv('MAIL_TO_USE=mailtrap');
        $_ENV['MAIL_TO_USE'] = 'mailtrap';
        $_SERVER['MAIL_TO_USE'] = 'mailtrap';

        try {
            $mailConfig = require config_path('mail.php');
        } finally {
            $previousProcess === false
                ? putenv('MAIL_TO_USE')
                : putenv('MAIL_TO_USE='.$previousProcess);

            if ($hadEnv) {
                $_ENV['MAIL_TO_USE'] = $previousEnv;
            } else {
                unset($_ENV['MAIL_TO_USE']);
            }

            if ($hadServer) {
                $_SERVER['MAIL_TO_USE'] = $previousServer;
            } else {
                unset($_SERVER['MAIL_TO_USE']);
            }
        }

        $this->assertSame('mailtrap', $mailConfig['default']);
    }
}
