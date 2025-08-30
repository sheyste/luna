<?php
/**
 * Email Helper for sending emails via SMTP
 * Works with AHA SEND API and other SMTP providers
 */

class EmailHelper
{
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $fromEmail;
    private $fromName;
    
    public function __construct()
    {
        $this->smtpHost = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
        $this->smtpPort = getenv('SMTP_PORT') ?: 587;
        $this->smtpUsername = getenv('SMTP_USERNAME') ?: '';
        $this->smtpPassword = getenv('SMTP_PASSWORD') ?: '';
        $this->fromEmail = getenv('FROM_EMAIL') ?: 'noreply@luna-mail.8800111.xyz';
        $this->fromName = getenv('FROM_NAME') ?: 'LUNA Inventory System';
    }
    
    /**
     * Read SMTP response
     */
    private function getSmtpResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 512)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') {
                break;
            }
        }
        return $response;
    }
    
    /**
     * Send email via SMTP
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @return array Result with success status and message
     */
    public function send($to, $subject, $body)
    {
        // Check if SMTP credentials are configured
        if (empty($this->smtpUsername) || empty($this->smtpPassword)) {
            return [
                'success' => false,
                'message' => 'SMTP credentials not configured. Please set SMTP_USERNAME and SMTP_PASSWORD in your .env file.'
            ];
        }
        
        try {
            // Create socket connection
            $socket = fsockopen('tcp://' . $this->smtpHost, $this->smtpPort, $errno, $errstr, 30);
            
            if (!$socket) {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to SMTP server: ' . $errstr
                ];
            }
            
            // Read server greeting
            $response = $this->getSmtpResponse($socket);
            if (substr($response, 0, 3) != '220') {
                fclose($socket);
                return [
                    'success' => false,
                    'message' => 'SMTP server greeting failed: ' . $response
                ];
            }
            
            // Send EHLO
            fwrite($socket, "EHLO localhost\r\n");
            $response = $this->getSmtpResponse($socket);
            if (substr($response, 0, 3) != '250') {
                fclose($socket);
                return [
                    'success' => false,
                    'message' => 'EHLO command failed: ' . $response
                ];
            }
            
            // Check if STARTTLS is supported
            if (strpos($response, 'STARTTLS') !== false) {
                // Start TLS
                fwrite($socket, "STARTTLS\r\n");
                $response = $this->getSmtpResponse($socket);
                if (substr($response, 0, 3) != '220') {
                    fclose($socket);
                    return [
                        'success' => false,
                        'message' => 'STARTTLS command failed: ' . $response
                    ];
                }
                
                // Enable crypto
                $cryptoEnabled = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if (!$cryptoEnabled) {
                    fclose($socket);
                    return [
                        'success' => false,
                        'message' => 'Failed to enable TLS encryption'
                    ];
                }
                
                // Send EHLO again after STARTTLS
                fwrite($socket, "EHLO localhost\r\n");
                $response = $this->getSmtpResponse($socket);
                if (substr($response, 0, 3) != '250') {
                    fclose($socket);
                    return [
                        'success' => false,
                        'message' => 'EHLO command after STARTTLS failed: ' . $response
                    ];
                }
            }
            
            // Authenticate using AUTH PLAIN (preferred method)
            $authString = base64_encode("\0" . $this->smtpUsername . "\0" . $this->smtpPassword);
            fwrite($socket, "AUTH PLAIN " . $authString . "\r\n");
            $response = $this->getSmtpResponse($socket);
            
            // If AUTH PLAIN fails, try AUTH LOGIN as fallback
            if (substr($response, 0, 3) != '235') {
                // Try AUTH LOGIN
                fwrite($socket, "AUTH LOGIN\r\n");
                $response = $this->getSmtpResponse($socket);
                if (substr($response, 0, 3) != '334') {
                    fclose($socket);
                    return [
                        'success' => false,
                        'message' => 'AUTH LOGIN command failed: ' . $response
                    ];
                }
                
                fwrite($socket, base64_encode($this->smtpUsername) . "\r\n");
                $response = $this->getSmtpResponse($socket);
                if (substr($response, 0, 3) != '334') {
                    fclose($socket);
                    return [
                        'success' => false,
                        'message' => 'Username authentication failed: ' . $response
                    ];
                }
                
                fwrite($socket, base64_encode($this->smtpPassword) . "\r\n");
                $response = $this->getSmtpResponse($socket);
                if (substr($response, 0, 3) != '235') {
                    fclose($socket);
                    return [
                        'success' => false,
                        'message' => 'Password authentication failed: ' . $response
                    ];
                }
            }
            
            // Set sender
            fwrite($socket, "MAIL FROM: <" . $this->fromEmail . ">\r\n");
            $response = $this->getSmtpResponse($socket);
            if (substr($response, 0, 3) != '250') {
                fclose($socket);
                return [
                    'success' => false,
                    'message' => 'MAIL FROM command failed: ' . $response
                ];
            }
            
            // Set recipient
            fwrite($socket, "RCPT TO: <" . $to . ">\r\n");
            $response = $this->getSmtpResponse($socket);
            if (substr($response, 0, 3) != '250') {
                fclose($socket);
                return [
                    'success' => false,
                    'message' => 'RCPT TO command failed: ' . $response
                ];
            }
            
            // Start data
            fwrite($socket, "DATA\r\n");
            $response = $this->getSmtpResponse($socket);
            if (substr($response, 0, 3) != '354') {
                fclose($socket);
                return [
                    'success' => false,
                    'message' => 'DATA command failed: ' . $response
                ];
            }
            
            // Prepare email headers and body
            $headers = "From: " . $this->fromName . " <" . $this->fromEmail . ">\r\n";
            $headers .= "Reply-To: " . $this->fromEmail . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Subject: " . $subject . "\r\n";
            
            // Send email data
            fwrite($socket, $headers . "\r\n");
            fwrite($socket, $body . "\r\n");
            fwrite($socket, ".\r\n");
            $response = $this->getSmtpResponse($socket);
            if (substr($response, 0, 3) != '250') {
                fclose($socket);
                return [
                    'success' => false,
                    'message' => 'Email data transmission failed: ' . $response
                ];
            }
            
            // Close connection
            fwrite($socket, "QUIT\r\n");
            $this->getSmtpResponse($socket); // Read QUIT response but don't check it
            fclose($socket);
            
            return [
                'success' => true,
                'message' => 'Email sent successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ];
        }
    }
}