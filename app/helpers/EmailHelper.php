<?php
/**
 * Email Helper for sending emails via SMTP server
 * Supports various SMTP providers
 */

class EmailHelper
{
    private $smtpHost;
    private $smtpPort;
    private $smtpUsername;
    private $smtpPassword;
    private $smtpSecurity;
    private $fromEmail;
    private $fromName;
    
    public function __construct()
    {
        $this->smtpHost = getenv('SMTP_HOST') ?: '';
        $this->smtpPort = getenv('SMTP_PORT') ?: 587;
        $this->smtpUsername = getenv('SMTP_USERNAME') ?: '';
        $this->smtpPassword = getenv('SMTP_PASSWORD') ?: '';
        $this->smtpSecurity = getenv('SMTP_SECURITY') ?: 'tls'; // tls, ssl, or none
        $this->fromEmail = getenv('FROM_EMAIL') ?: 'noreply@ahasend.com';
        $this->fromName = getenv('FROM_NAME') ?: 'LUNA Inventory System';
        
        // Debug logging to check environment variables
        error_log("EmailHelper Debug - FROM_EMAIL from env: " . (getenv('FROM_EMAIL') ?: 'NOT SET'));
        error_log("EmailHelper Debug - Final fromEmail: " . $this->fromEmail);
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
        // Check if SMTP configuration is set
        if (empty($this->smtpHost)) {
            return [
                'success' => false,
                'message' => 'SMTP host not configured. Please set SMTP_HOST in your .env file.'
            ];
        }
        
        if (empty($this->smtpUsername) || empty($this->smtpPassword)) {
            return [
                'success' => false,
                'message' => 'SMTP credentials not configured. Please set SMTP_USERNAME and SMTP_PASSWORD in your .env file.'
            ];
        }
        
        try {
            // Create a unique boundary
            $boundary = uniqid('np');
            
            // Prepare headers
            $headers = [
                "MIME-Version: 1.0",
                "Content-Type: multipart/alternative; boundary=\"{$boundary}\"",
                "From: {$this->fromName} <{$this->fromEmail}>",
                "Reply-To: {$this->fromEmail}",
                "X-Mailer: LUNA Inventory System",
                "X-Priority: 3"
            ];
            
            // Create email body with both plain text and HTML
            $plainTextBody = $this->wrapLines(strip_tags($body), 76);
            $htmlBody = $this->wrapLines($body, 76);
            
            $emailBody = "--{$boundary}\r\n";
            $emailBody .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $emailBody .= $plainTextBody . "\r\n\r\n";
            $emailBody .= "--{$boundary}\r\n";
            $emailBody .= "Content-Type: text/html; charset=UTF-8\r\n";
            $emailBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $emailBody .= $htmlBody . "\r\n\r\n";
            $emailBody .= "--{$boundary}--";
            
            // Log the attempt for debugging
            error_log("SMTP Email Attempt: Sending to {$to} via {$this->smtpHost}:{$this->smtpPort} using {$this->smtpSecurity}");
            error_log("SMTP Credentials: Username={$this->smtpUsername}, Password=" . (empty($this->smtpPassword) ? 'EMPTY' : 'SET'));
            
            // Try to send using SMTP socket connection
            $result = $this->sendViaSMTP($to, $subject, $emailBody, $headers);
            
            if ($result['success']) {
                error_log("SMTP Email sent successfully to: {$to}");
                return [
                    'success' => true,
                    'message' => 'Email sent successfully via SMTP',
                    'smtp_host' => $this->smtpHost
                ];
            } else {
                // Don't fallback to PHP mail() - we want to use SMTP only
                error_log("SMTP failed for: {$to}. Error: " . $result['message']);
                return [
                    'success' => false,
                    'message' => 'SMTP connection failed: ' . $result['message']
                ];
            }
        } catch (Exception $e) {
            error_log("SMTP Email Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Send email via direct SMTP socket connection
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email body
     * @param array $headers Email headers
     * @return array Result with success status and message
     */
    private function sendViaSMTP($to, $subject, $body, $headers)
    {
        try {
            // Create socket connection
            $socket = $this->createSMTPConnection();
            
            if (!$socket) {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to SMTP server'
                ];
            }
            
            // Perform SMTP conversation
            $result = $this->performSMTPConversation($socket, $to, $subject, $body, $headers);
            
            // Close connection
            fclose($socket);
            
            return $result;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'SMTP Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create SMTP socket connection
     * 
     * @return resource|false Socket resource or false on failure
     */
    private function createSMTPConnection()
    {
        $context = stream_context_create();
        
        // Create context with SSL/TLS options
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        // Handle SSL/TLS - for TLS we start plain then upgrade
        if (strtolower($this->smtpSecurity) === 'ssl') {
            $host = "ssl://{$this->smtpHost}";
        } else {
            $host = $this->smtpHost;
        }
        
        // Create socket connection
        $socket = stream_socket_client(
            "{$host}:{$this->smtpPort}",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );
        
        if (!$socket) {
            error_log("SMTP Connection failed to {$host}:{$this->smtpPort}: {$errno} - {$errstr}");
            return false;
        }
        
        // Set timeout for socket operations
        stream_set_timeout($socket, 30);
        
        // Read server greeting
        $response = fgets($socket, 512);
        if (!$response || substr($response, 0, 3) !== '220') {
            error_log("SMTP Server greeting error: " . ($response ?: 'No response'));
            fclose($socket);
            return false;
        }
        
        return $socket;
    }
    
    /**
     * Perform SMTP conversation
     * 
     * @param resource $socket Socket connection
     * @param string $to Recipient email
     * @param string $subject Email subject  
     * @param string $body Email body
     * @param array $headers Email headers
     * @return array Result with success status and message
     */
    private function performSMTPConversation($socket, $to, $subject, $body, $headers)
    {
        try {
            // Send EHLO
            $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
            fwrite($socket, "EHLO {$serverName}\r\n");
            $response = fgets($socket, 512);
            
            // Read multi-line EHLO response
            while ($response && substr($response, 3, 1) === '-') {
                $response = fgets($socket, 512);
            }
            
            if (!$response || substr($response, 0, 3) !== '250') {
                return ['success' => false, 'message' => "EHLO failed: {$response}"];
            }
            
            // Handle STARTTLS if required
            if (strtolower($this->smtpSecurity) === 'tls') {
                error_log("SMTP: Sending STARTTLS command");
                fwrite($socket, "STARTTLS\r\n");
                $response = fgets($socket, 512);
                error_log("SMTP: STARTTLS response: " . trim($response));
                
                if (!$response || substr($response, 0, 3) !== '220') {
                    return ['success' => false, 'message' => "STARTTLS failed. Expected 220, got: " . trim($response)];
                }
                
                // Enable crypto with better options
                error_log("SMTP: Enabling TLS encryption");
                $cryptoResult = stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_TLS_CLIENT);
                if (!$cryptoResult) {
                    return ['success' => false, 'message' => "Failed to enable TLS encryption"];
                }
                error_log("SMTP: TLS encryption enabled successfully");
                
                // Send EHLO again after TLS
                $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
                error_log("SMTP: Sending EHLO after TLS: {$serverName}");
                fwrite($socket, "EHLO {$serverName}\r\n");
                $response = fgets($socket, 512);
                
                // Read multi-line EHLO response
                while ($response && substr($response, 3, 1) === '-') {
                    $response = fgets($socket, 512);
                }
                
                error_log("SMTP: EHLO after TLS response: " . trim($response));
                if (!$response || substr($response, 0, 3) !== '250') {
                    return ['success' => false, 'message' => "EHLO after TLS failed: {$response}"];
                }
            }
            
            // Authenticate using PLAIN method (as required by AhaSend)
            $authString = base64_encode("\0" . $this->smtpUsername . "\0" . $this->smtpPassword);
            fwrite($socket, "AUTH PLAIN {$authString}\r\n");
            $response = fgets($socket, 512);
            if (substr($response, 0, 3) !== '235') {
                // Try LOGIN method as fallback
                fwrite($socket, "AUTH LOGIN\r\n");
                $response = fgets($socket, 512);
                if (substr($response, 0, 3) !== '334') {
                    return ['success' => false, 'message' => "AUTH PLAIN and LOGIN failed: {$response}"];
                }
                
                // Send username
                fwrite($socket, base64_encode($this->smtpUsername) . "\r\n");
                $response = fgets($socket, 512);
                if (substr($response, 0, 3) !== '334') {
                    return ['success' => false, 'message' => "Username authentication failed: {$response}"];
                }
                
                // Send password
                fwrite($socket, base64_encode($this->smtpPassword) . "\r\n");
                $response = fgets($socket, 512);
                if (substr($response, 0, 3) !== '235') {
                    return ['success' => false, 'message' => "Password authentication failed: {$response}"];
                }
            }
            
            // Send MAIL FROM
            fwrite($socket, "MAIL FROM:<{$this->fromEmail}>\r\n");
            $response = fgets($socket, 512);
            if (substr($response, 0, 3) !== '250') {
                return ['success' => false, 'message' => "MAIL FROM failed: {$response}"];
            }
            
            // Send RCPT TO
            fwrite($socket, "RCPT TO:<{$to}>\r\n");
            $response = fgets($socket, 512);
            if (substr($response, 0, 3) !== '250') {
                return ['success' => false, 'message' => "RCPT TO failed: {$response}"];
            }
            
            // Send DATA
            fwrite($socket, "DATA\r\n");
            $response = fgets($socket, 512);
            if (substr($response, 0, 3) !== '354') {
                return ['success' => false, 'message' => "DATA command failed: {$response}"];
            }
            
            // Send headers and body - wrap lines to avoid "line too long" error
            $wrappedSubject = $this->wrapLines($subject, 76);
            fwrite($socket, "Subject: {$wrappedSubject}\r\n");
            fwrite($socket, "To: {$to}\r\n");
            foreach ($headers as $header) {
                $wrappedHeader = $this->wrapLines($header, 76);
                fwrite($socket, "{$wrappedHeader}\r\n");
            }
            fwrite($socket, "\r\n"); // Empty line between headers and body
            
            // Send body with proper line wrapping
            $wrappedBody = $this->wrapLines($body, 76);
            fwrite($socket, $wrappedBody);
            fwrite($socket, "\r\n.\r\n"); // End data with dot
            
            $response = fgets($socket, 512);
            if (substr($response, 0, 3) !== '250') {
                return ['success' => false, 'message' => "Message sending failed: {$response}"];
            }
            
            // Send QUIT
            fwrite($socket, "QUIT\r\n");
            fgets($socket, 512);
            
            return ['success' => true, 'message' => 'Email sent successfully via SMTP'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'SMTP conversation error: ' . $e->getMessage()];
        }
    }
    
    /**
     * Wrap long lines to comply with SMTP line length limits
     * 
     * @param string $text Text to wrap
     * @param int $length Maximum line length (default 76)
     * @return string Wrapped text
     */
    private function wrapLines($text, $length = 76)
    {
        // Don't wrap HTML tags - split by lines first
        $lines = explode("\n", $text);
        $wrappedLines = [];
        
        foreach ($lines as $line) {
            $line = rtrim($line, "\r");
            
            // If line is shorter than limit, keep as is
            if (strlen($line) <= $length) {
                $wrappedLines[] = $line;
                continue;
            }
            
            // For HTML content, be more careful about wrapping
            if (strpos($line, '<') !== false && strpos($line, '>') !== false) {
                // This is likely HTML, try to wrap at spaces while preserving tags
                $wrappedLines = array_merge($wrappedLines, $this->wrapHtmlLine($line, $length));
            } else {
                // Plain text - wrap at word boundaries
                $wrappedLines = array_merge($wrappedLines, $this->wrapTextLine($line, $length));
            }
        }
        
        return implode("\r\n", $wrappedLines);
    }
    
    /**
     * Wrap a plain text line
     */
    private function wrapTextLine($line, $length)
    {
        $words = explode(' ', $line);
        $lines = [];
        $currentLine = '';
        
        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $length) {
                $currentLine = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            } else {
                if ($currentLine !== '') {
                    $lines[] = $currentLine;
                }
                // If single word is too long, break it
                if (strlen($word) > $length) {
                    $lines = array_merge($lines, str_split($word, $length));
                    $currentLine = '';
                } else {
                    $currentLine = $word;
                }
            }
        }
        
        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }
        
        return $lines;
    }
    
    /**
     * Wrap HTML line more carefully
     */
    private function wrapHtmlLine($line, $length)
    {
        // For HTML, just do basic wrapping at spaces, being careful not to break tags
        $result = [];
        $currentLine = '';
        $inTag = false;
        
        for ($i = 0; $i < strlen($line); $i++) {
            $char = $line[$i];
            
            if ($char === '<') {
                $inTag = true;
            } elseif ($char === '>') {
                $inTag = false;
            }
            
            $currentLine .= $char;
            
            // Only wrap at spaces when not inside a tag
            if (!$inTag && $char === ' ' && strlen($currentLine) > $length) {
                $result[] = rtrim($currentLine);
                $currentLine = '';
            }
        }
        
        if ($currentLine !== '') {
            $result[] = $currentLine;
        }
        
        return $result;
    }
    
    /**
     * Test SMTP connection
     * 
     * @return array Result with success status and message
     */
    public function testConnection()
    {
        try {
            $socket = $this->createSMTPConnection();
            
            if (!$socket) {
                return [
                    'success' => false,
                    'message' => 'Failed to connect to SMTP server'
                ];
            }
            
            // Test EHLO
            fwrite($socket, "EHLO {$_SERVER['SERVER_NAME']}\r\n");
            $response = fgets($socket, 512);
            
            // Close connection
            fwrite($socket, "QUIT\r\n");
            fgets($socket, 512);
            fclose($socket);
            
            if (substr($response, 0, 3) === '250') {
                return [
                    'success' => true,
                    'message' => 'SMTP connection successful'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'SMTP server responded with: ' . trim($response)
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'SMTP test failed: ' . $e->getMessage()
            ];
        }
    }
}