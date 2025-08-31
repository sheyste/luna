<?php
/**
 * Email Helper for sending emails via AhaSend API V2
 * Works with AHA SEND API V2 and other email providers
 */

class EmailHelper
{
    private $apiKey;
    private $apiUrl;
    private $accountId;
    private $fromEmail;
    private $fromName;
    
    public function __construct()
    {
        $this->apiKey = getenv('AHASEND_API_KEY') ?: '';
        $this->apiUrl = getenv('AHASEND_API_URL') ?: 'https://api.ahasend.com';
        $this->accountId = getenv('ACCOUNT_ID') ?: '';
        $this->fromEmail = getenv('FROM_EMAIL') ?: 'noreply@luna-mail.8800111.xyz';
        $this->fromName = getenv('FROM_NAME') ?: 'LUNA Inventory System';
    }
    
    /**
     * Send email via AhaSend API V2
     * 
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @return array Result with success status and message
     */
    public function send($to, $subject, $body)
    {
        // Check if API key is configured
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'AhaSend API key not configured. Please set AHASEND_API_KEY in your .env file.'
            ];
        }
        
        // Check if account ID is configured
        if (empty($this->accountId)) {
            return [
                'success' => false,
                'message' => 'AhaSend Account ID not configured. Please set ACCOUNT_ID in your .env file.'
            ];
        }
        
        try {
            // Prepare the API request data
            $data = [
                'from' => [
                    'email' => $this->fromEmail,
                    'name' => $this->fromName
                ],
                'recipients' => [
                    [
                        'email' => $to
                    ]
                ],
                'subject' => $subject,
                'html_content' => $body,
                'text_content' => strip_tags($body)
            ];
            
            // Convert data to JSON
            $jsonData = json_encode($data);
            
            // Construct the full API endpoint URL
            $endpoint = $this->apiUrl . '/v2/accounts/' . $this->accountId . '/messages';
            
            // Log the request for debugging
            error_log("AhaSend API Request: " . $endpoint . " with data: " . $jsonData);
            
            // Initialize cURL
            $ch = curl_init();
            
            // Set cURL options
            curl_setopt_array($ch, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey
                ],
                CURLOPT_TIMEOUT => 30,
                CURLOPT_VERBOSE => true,
                CURLOPT_HEADER => true
            ]);
            
            // Execute the request
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $error = curl_error($ch);
            
            // Close cURL
            curl_close($ch);
            
            // Check for cURL errors
            if ($error) {
                error_log("AhaSend API cURL Error: " . $error);
                return [
                    'success' => false,
                    'message' => 'cURL error: ' . $error
                ];
            }
            
            // Separate headers and body
            $headers = substr($response, 0, $headerSize);
            $bodyResponse = substr($response, $headerSize);
            
            // Log the response for debugging
            error_log("AhaSend API Response Headers: " . $headers);
            error_log("AhaSend API Response Body: " . $bodyResponse);
            
            // Decode the response
            $responseData = json_decode($bodyResponse, true);
            
            // Check HTTP status code
            if ($httpCode >= 200 && $httpCode < 300) {
                return [
                    'success' => true,
                    'message' => 'Email sent successfully',
                    'data' => $responseData
                ];
            } else {
                // Handle API errors
                $errorMessage = 'API error (HTTP ' . $httpCode . ')';
                if (isset($responseData['message'])) {
                    $errorMessage = $responseData['message'];
                } elseif (isset($responseData['error'])) {
                    $errorMessage = $responseData['error'];
                } elseif ($httpCode == 404) {
                    $errorMessage = 'Endpoint not found. Please check the API URL and endpoint.';
                }
                
                return [
                    'success' => false,
                    'message' => 'Failed to send email: ' . $errorMessage,
                    'http_code' => $httpCode,
                    'response' => $responseData,
                    'headers' => $headers
                ];
            }
        } catch (Exception $e) {
            error_log("AhaSend API Exception: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ];
        }
    }
}