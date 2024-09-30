<!-- part-search-form.php /** DANA API Search**/-->
<form method="GET" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <label for="partNumber">Enter Part Number:</label>
    <input type="text" id="partNumber" name="partNumber" required>
    <button type="submit">Search</button>
</form>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['partNumber'])) {
    $partNumber = sanitize_text_field($_GET['partNumber']); // Sanitize input

    // Define the API URL and key
    $api_url = 'https://api.danaaftermarket.com/pzp/part/api/v1/getPart'; // Replace with the actual API URL
    $api_key = 'DxEXc7DL0pxKrLUFkuBOYX7mmzNA1iVv'; // Replace with your API key

    // Prepare the API request URL
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
            "apiKey: $api_key",           // API Key as part of the headers
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

            // Debugging: print raw response
            echo '<pre>';
            print_r($data);  // This will print the entire response
            echo '</pre>';
			echo "<script>console.log($data);</script>";

            // Check if there are parts in the response
            if (isset($data['parts']) && count($data['parts']) > 0) {
                foreach ($data['parts'] as $part) {
                    ?>
                    <div class="part-detail">
                        <h3>Part Number: <?php echo esc_html($part['partNumber']); ?></h3>
                        <p>Description: <?php echo esc_html($part['partDesc']); ?></p>
                        <p>Type: <?php echo esc_html($part['partType']); ?></p>
                        <p>Long Description: <?php echo esc_html($part['longDesc']); ?></p>
                        <p>Sales Description: <?php echo esc_html($part['salesDesc']); ?></p>
                        <p>Application Type: <?php echo esc_html($part['applicationType']); ?></p>
                        <p>Application Notes: <?php echo esc_html($part['applicationNotes']); ?></p>
                        <p>Package Type: <?php echo esc_html($part['packageType']); ?></p>
                        <p>Package Quantity: <?php echo esc_html($part['packageQty']); ?></p>
                        <p>Category: <?php echo esc_html($part['category']['name']); ?></p>
                        <p>Brand: <?php echo esc_html($part['brand']); ?></p>
                        <?php if (!empty($part['thumbNailImageURL'])) { ?>
                            <img src="<?php echo esc_url($part['thumbNailImageURL']); ?>" alt="Thumbnail">
                        <?php } ?>
                    </div>
                    <?php
                }
            } else {
                echo 'No parts found for this part number.';
            }
        }
    }

    // Close the cURL session
    curl_close($curl);
}
?>
