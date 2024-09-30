<!--  DANA API Pricing  -->
<form method="GET" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <label for="partNumber">Enter Part Number:</label>
    <input type="text" id="partNumber" name="partNumber" required>
    <button type="submit">Search</button>
</form>
 
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['partNumber'])) {
    $partNumber = sanitize_text_field($_GET['partNumber']); // Sanitize input
	    $customerNumber = '0000500637'; // Customer number, using Yoni account here, replace with new one if needed.

    // Define the Pricing API URL and key
    $api_url = 'https://api.danaaftermarket.com/pzp/price/api/v1/getPrice'; // 
    $api_key = 'DxEXc7DL0pxKrLUFkuBOYX7mmzNA1iVv'; // 

    // Prepare the API request URL
    $request_url = "$api_url?partNumber=" . urlencode($partNumber) . "&customerNumber=" . $customerNumber;

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

            // Check if the response contains the part pricing details
            if (isset($data['partNumber'])) {
                $standardPrice = $data['standardPrice'];
                $unitWeightGross = $data['unitWeightGross'];
                $uom = $data['uom'];
                $packagingQuantity = $data['packagingQuantity'];
                $unitCoreDeposit = $data['unitCoreDeposit'];

                // Print the pricing details
                echo "<div class='pricing-details'>";
                echo "<p><strong>Standard Price:</strong> $" . esc_html($standardPrice) . "</p>";
                echo "<p><strong>Gross Weight:</strong> " . esc_html($unitWeightGross) . " " . esc_html($uom) . "</p>";
                echo "<p><strong>Packaging Quantity:</strong> " . esc_html($packagingQuantity) . "</p>";

                // Only show Unit Core Deposit if it exists (greater than 0)
                if ($unitCoreDeposit > 0) {
                    echo "<p><strong>Unit Core Deposit:</strong> $" . esc_html($unitCoreDeposit) . "</p>";
                } else {
                    echo "<p><strong>Unit Core Deposit:</strong> None</p>";
                }

                echo "</div>";
            } else {
                echo 'No pricing details found for this part number.';
            }
        }
    }

    // Close the cURL session
    curl_close($curl);
}
?>
