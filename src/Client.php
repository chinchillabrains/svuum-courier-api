<?php namespace Chinchillabrains\Svuumapi;

/**
 * Client class for communicating with Svuum API
 */
class Client{

    private $apiUrl;
    private $merchantCode;
    private $apiKey;

    public function __construct( $merchantCode, $apiKey )
    {
        $this->apiKey = $apiKey;
        $this->merchantCode = $merchantCode;
        $this->apiUrl = "https://live.svuum.com/api/v1";
    }

    /**
     * Create a new package to send
     * 
     * @param array $packageInfo Fields to send to web service
     * 
     * @return array Web service response
     */
    public function sendPackage( array $packageInfo ): array
    {
        $endpoint = $this->apiUrl . "/svmqa/new_process/add_process?api_key={$this->apiKey}";
        
        /* Available fields (details in docs/documentation.pdf)
        */
        return $this->sendRequest( $endpoint, ['json' => $packageInfo] );
    }


    /**
     * Get tracking data for a package
     * 
     * @param array $packageInfo Fields to send to web service
     * 
     * @return array Web service response
     */
    public function getTrackingData( array $packageInfo ): array
    {
        $endpoint = $this->apiUrl . "/process_track_api?api_key={$this->apiKey}";
        
        /* Available fields (details in docs/documentation.pdf)
        */
        return $this->sendRequest( $endpoint, ['query' => $packageInfo], 'GET' );
    }

    /**
     * Regenerate PDF
     * 
     * @param array $packageInfo Fields to send to web service
     * 
     * @return array Web service response
     */
    public function regeneratePDF( array $packageInfo ): array
    {
        $endpoint = $this->apiUrl . "/process/get_process_pdf?api_key={$this->apiKey}";
        
        /* Available fields (details in docs/documentation.pdf)
        */
        return $this->sendRequest( $endpoint, ['query' => $packageInfo], 'GET' );
    }

    /**
     * Send request to the web service
     * 
     * @param string $endpoint Selected API endpoint
     * @param array $requestData Fields to send to web service
     * @param string $method HTTP method to use. Default: POST
     * 
     * @return array Web service response
     */
    private function sendRequest( string $endpoint, array $requestData, $method = 'POST' ): array
    {
        if ( ! isset( $requestData['query'] ) ) {
            $requestData['query'] = [];
        }
        if ( ! isset( $requestData['json'] ) ) {
            $requestData['json'] = [];
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request( $method, $endpoint, [
            'headers' => [
                'Content-Type'  => 'application/json'
            ],
            'query' => $requestData['query'],
            'json' => $requestData['json']
        ] );

        return $response->getBody();
    }

}