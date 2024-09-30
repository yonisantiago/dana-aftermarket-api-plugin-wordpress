# Dana API Parts Search - WordPress Integration

This project integrates the Dana Aftermarket API into a WordPress website to allow users to search for parts based on part number and vehicle application details such as application type, year, make, and model. The results display part details returned by the API.

## Features
- Search for parts based on vehicle details using a simple form.
- Display results fetched from Dana API including part number, description, and images.
- Handles errors and missing data gracefully.

## Requirements
- WordPress environment.
- cURL and PHP 7.4+.
- Dana API credentials.

## Installation
1. **Add the form to your theme:**
   - Copy the `application-search-form.php` file to your WordPress theme.
   - Include it in a page template or use it within a custom page.

2. **Configure API Key:**
   - Set your Dana API key in the PHP file where the request is made.
   - Example: `$api_key = 'YOUR_API_KEY';`

3. **Ensure Permalinks are configured:**
   - Go to **Settings > Permalinks** and click **Save Changes** to flush rewrite rules.

4. **Handle GET Requests:**
   - The GET request is triggered from the form submission and processed using PHP and cURL to fetch data from the Dana Aftermarket API.

5. **Enable Debugging (Optional):**
   - Modify `wp-config.php` to enable debugging if needed:
     ```php
     define('WP_DEBUG', true);
     define('WP_DEBUG_LOG', true);
     ```

## Usage
- Navigate to the page with the form, fill in the vehicle details, and click "Search."
- The API will return part details based on the input.

## Troubleshooting
- Ensure API key is valid.
- Check for plugin conflicts that might alter URL routing.
- If URLs result in 404 errors, check permalinks and routing rules.

## License
This project is provided as-is for integration with the Dana Aftermarket API in WordPress environments.
