<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * FROM EMAIL
     * --------------------------------------------------------------------------
     */
    public string $fromEmail = 'websiteBinan@gmail.com';

    public string $fromName = 'Website Binan';

    public string $recipients = '';

    /**
     * --------------------------------------------------------------------------
     * USER AGENT
     * --------------------------------------------------------------------------
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * --------------------------------------------------------------------------
     * MAIL PROTOCOL
     * --------------------------------------------------------------------------
     * mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * --------------------------------------------------------------------------
     * SENDMAIL PATH
     * --------------------------------------------------------------------------
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * --------------------------------------------------------------------------
     * SMTP CONFIGURATION
     * --------------------------------------------------------------------------
     */
    public string $SMTPHost = 'smtp.gmail.com';

    public string $SMTPUser = 'websiteBinan@gmail.com';

    // GMAIL APP PASSWORD
    public string $SMTPPass = 'djkh tkzp jphu kchp';

    public int $SMTPPort = 587;

    public int $SMTPTimeout = 30;

    public bool $SMTPKeepAlive = false;

    /**
     * tls or ssl
     */
    public string $SMTPCrypto = 'tls';

    public bool $SMTPAutoTLS = true;

    /**
     * --------------------------------------------------------------------------
     * EMAIL SETTINGS
     * --------------------------------------------------------------------------
     */
    public bool $wordWrap = true;

    public int $wrapChars = 76;

    /**
     * text or html
     */
    public string $mailType = 'html';

    public string $charset = 'UTF-8';

    public bool $validate = true;

    public int $priority = 3;

    /**
     * --------------------------------------------------------------------------
     * NEWLINES
     * --------------------------------------------------------------------------
     */
    public string $CRLF = "\r\n";

    public string $newline = "\r\n";

    /**
     * --------------------------------------------------------------------------
     * BCC
     * --------------------------------------------------------------------------
     */
    public bool $BCCBatchMode = false;

    public int $BCCBatchSize = 200;

    /**
     * --------------------------------------------------------------------------
     * DELIVERY STATUS NOTIFICATION
     * --------------------------------------------------------------------------
     */
    public bool $DSN = false;
}