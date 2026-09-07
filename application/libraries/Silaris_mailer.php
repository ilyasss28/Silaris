<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Transactional email gateway for account-related messages.
 *
 * SMTP credentials are read exclusively from environment variables so they
 * never need to be committed to the repository. When no SMTP host is set,
 * CodeIgniter falls back to the hosting provider's PHP mail transport.
 */
class Silaris_mailer
{
    private $CI;
    private $last_error = '';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('email');
        $this->CI->load->helper('url');
    }

    public function send_password_reset($recipient, $full_name, $reset_url)
    {
        return $this->send(
            $recipient,
            'Atur Ulang Password SILARIS',
            'Permintaan Atur Ulang Password',
            'Halo '.html_escape($full_name ?: 'Pengguna SILARIS').', permintaan untuk mengatur ulang password akun Anda telah diterima.',
            'Atur Ulang Password',
            $reset_url,
            'Tautan ini berlaku selama 1 jam. Abaikan email ini apabila Anda tidak merasa mengajukan permintaan tersebut.'
        );
    }

    public function send_registration_pending($recipient, $full_name)
    {
        return $this->send(
            $recipient,
            'Pendaftaran Akun SILARIS Diterima',
            'Akun Menunggu Verifikasi Admin',
            'Halo '.html_escape($full_name ?: 'Pengguna SILARIS').', akun Anda berhasil dibuat dan saat ini belum aktif.',
            '',
            '',
            'Admin akan memeriksa kesesuaian akun dengan data resmi. Anda dapat masuk setelah admin memverifikasi dan mengaktifkan akun.'
        );
    }

    public function get_last_error()
    {
        return $this->last_error;
    }

    private function send($recipient, $subject, $heading, $message, $action_label = '', $action_url = '', $footer = '')
    {
        $recipient = strtolower(trim((string) $recipient));
        if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->last_error = 'Alamat email penerima tidak valid.';
            return false;
        }

        $config = $this->mail_config();
        $this->CI->email->clear(true);
        $this->CI->email->initialize($config);
        $this->CI->email->from($this->from_address(), $this->from_name());
        $this->CI->email->to($recipient);
        $this->CI->email->subject($subject);
        $this->CI->email->message($this->render_message($heading, $message, $action_label, $action_url, $footer));
        $this->CI->email->set_alt_message(trim(strip_tags($message.' '.$action_url.' '.$footer)));

        if ($this->CI->email->send(false)) {
            $this->last_error = '';
            return true;
        }

        $this->last_error = 'Layanan email belum dapat mengirim pesan. Periksa konfigurasi mail server.';
        log_message('error', 'SILARIS transactional email failed for recipient domain: '.substr(strrchr($recipient, '@') ?: '', 1));
        return false;
    }

    private function mail_config()
    {
        $smtp_host = trim((string) getenv('SILARIS_SMTP_HOST'));
        $config = array(
            'protocol' => $smtp_host !== '' ? 'smtp' : 'mail',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => true,
            'newline' => "\r\n",
            'crlf' => "\r\n",
        );

        if ($smtp_host !== '') {
            $config['smtp_host'] = $smtp_host;
            $config['smtp_user'] = (string) getenv('SILARIS_SMTP_USER');
            $config['smtp_pass'] = (string) getenv('SILARIS_SMTP_PASS');
            $config['smtp_port'] = (int) (getenv('SILARIS_SMTP_PORT') ?: 587);
            $config['smtp_timeout'] = 15;
            $crypto = strtolower(trim((string) getenv('SILARIS_SMTP_CRYPTO')));
            if (in_array($crypto, array('tls', 'ssl'), true)) {
                $config['smtp_crypto'] = $crypto;
            }
        }

        return $config;
    }

    private function from_address()
    {
        $configured = trim((string) getenv('SILARIS_MAIL_FROM'));
        if (filter_var($configured, FILTER_VALIDATE_EMAIL)) {
            return $configured;
        }

        $site_email = function_exists('get_option') ? trim((string) get_option('email')) : '';
        if (filter_var($site_email, FILTER_VALIDATE_EMAIL)) {
            return $site_email;
        }

        $host = preg_replace('/:\d+$/', '', (string) $this->CI->input->server('HTTP_HOST'));
        $host = preg_replace('/^www\./i', '', $host);
        if ($host !== '' && strpos($host, '.') !== false && filter_var('noreply@'.$host, FILTER_VALIDATE_EMAIL)) {
            return 'noreply@'.$host;
        }

        return 'noreply@silaris.id';
    }

    private function from_name()
    {
        $configured = trim((string) getenv('SILARIS_MAIL_FROM_NAME'));
        return $configured !== '' ? $configured : 'SILARIS Kemenkum Sulawesi Tenggara';
    }

    private function render_message($heading, $message, $action_label, $action_url, $footer)
    {
        $button = '';
        if ($action_label !== '' && filter_var($action_url, FILTER_VALIDATE_URL)) {
            $button = '<p style="margin:28px 0"><a href="'.html_escape($action_url).'" style="background:#05063e;color:#fff;text-decoration:none;padding:13px 22px;border-radius:8px;font-weight:700;display:inline-block">'.html_escape($action_label).'</a></p>';
        }

        return '<!doctype html><html><body style="margin:0;background:#f3f5f9;font-family:Arial,sans-serif;color:#0f1b2d">'
            .'<table role="presentation" width="100%" cellspacing="0" cellpadding="0"><tr><td style="padding:32px 16px">'
            .'<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;margin:auto;background:#fff;border-top:4px solid #fecd08;border-radius:12px">'
            .'<tr><td style="padding:30px"><p style="margin:0 0 8px;color:#8a6c00;font-size:12px;font-weight:700;letter-spacing:.08em">SILARIS</p>'
            .'<h1 style="margin:0 0 16px;font-size:22px">'.html_escape($heading).'</h1>'
            .'<p style="margin:0;line-height:1.7;color:#526174">'.$message.'</p>'.$button
            .'<p style="margin:18px 0 0;line-height:1.6;font-size:13px;color:#6b7686">'.html_escape($footer).'</p>'
            .'</td></tr></table></td></tr></table></body></html>';
    }
}
