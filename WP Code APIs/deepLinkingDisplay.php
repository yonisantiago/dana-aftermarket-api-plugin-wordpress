<!-- Dana Get Deep Linking Display by part search-->
<form method="GET" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
    <label for="partNumber">Enter Part Number:</label>
    <input type="text" id="partNumber" name="partNumber" required>
    <button type="submit">Search</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['partNumber'])) {
    $partNumber = sanitize_text_field($_GET['partNumber']); // Sanitize input
    $api_key = 'DxEXc7DL0pxKrLUFkuBOYX7mmzNA1iVv'; // Replace with your actual API key

    // Output the embed element with the dynamic partNumber and API key
    echo '<embed src="https://danaaftermarket.com/partdetails?partNumber=' . esc_attr($partNumber) . '&key=' . esc_attr($api_key) . '" width="100%" height="100%" type="text/html">';
}
?>
