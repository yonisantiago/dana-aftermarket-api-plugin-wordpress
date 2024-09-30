<!-- DANA API Availability -->
<form method="GET" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <label for="partNumber">Enter Part Number:</label>
    <input type="text" id="partNumber" name="partNumber" required>
    <button type="submit">Search</button>
</form>
 
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['partNumber'])) {
    $partNumber = sanitize_text_field($_GET['partNumber']); // Sanitize input

    // Defining the API URL and key
    $api_url = 'https://api.danaaftermarket.com/pzp/api/v1/getInventoryAvailability'; // API Url
    $api_key = 'DxEXc7DL0pxKrLUFkuBOYX7mmzNA1iVv';//API key

    // Preparing the API request URL
    $request_url = "$api_url?partNumber=" . urlencode($partNumber);

    // Initialize cURL
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => $request_url,       // API URL with parameters
        CURLOPT_RETURNTRANSFER => true,    // Return the response instead of printing it
        CURLOPT_TIMEOUT => 30,             // Timeout duration
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "apiKey: $api_key",           // API Key in the header
            "cache-control: no-cache"
        ],
    ]);

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        echo 'cURL Error: ' . esc_html($error_msg); // Sanitize error message output
    } else {
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get the status code

        // Check if the status code is 200 OK
        if ($status_code != 200) {
            echo 'API request failed with status code: ' . esc_html($status_code);
        } else {
            // Decode the JSON response
            $data = json_decode($response, true);

            // Check if part details and availability details exist
            if (isset($data['partDetail']['availabilityDtls']) && count($data['partDetail']['availabilityDtls']) > 0) {
                foreach ($data['partDetail']['availabilityDtls'] as $availability) {
                    $qtyAvail = $availability['qtyAvail'];
                    $inventoryType = $availability['inventoryType'];
                    $leadTimeInDays = $availability['leadTimeInDays'];

                    // Translate inventory types
                    switch ($inventoryType) {
                        case 'FG':
                            $inventoryDescription = 'Finished Goods';
                            break;
                        case 'BULK':
                            $inventoryDescription = 'Available as Bulk parts for assembly';
                            break;
                        case 'BTO':
                            $inventoryDescription = 'Regular Plant Build-To-Order';
                            break;
                        case 'SLTI':
                            $inventoryDescription = 'Standard-Lead-Time-Build-To-Order (Parts that cannot be delivered as FG or BTO)';
                            break;
                        default:
                            $inventoryDescription = 'Unknown Inventory Type';
                    }

                    // Print the availability details
                    echo "<div class='availability-details'>";
                    echo "<p><strong>Quantity Available:</strong> " . esc_html($qtyAvail) . "</p>";
                    echo "<p><strong>Inventory Type:</strong> " . esc_html($inventoryType) . " (" . esc_html($inventoryDescription) . ")</p>";
                    echo "<p><strong>Lead Time in Days:</strong> " . esc_html($leadTimeInDays) . "</p>";
                    echo "</div>";
                }
            } else {
                echo 'No availability details found for this part number.';
            }
        }
    }

    // Close the cURL session
    curl_close($curl);
}
?>
