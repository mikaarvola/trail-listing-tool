# trail_listing_tool
Tool for listing items from [Trail Asset Management](https://trail.fi/). To use this tool you have to get an API Token from the Trail service.

## Key features
- currently seraching from one department only
- two model ids supported
- free text search
- one location

## How to use
1. Clone repository to your web server
2. Copy config-sample.php as config.php
3. Edit config.php and paste your personal `API key` and `department id` which you would like to include in search
4. Open https://your_web_server/trail.php in your web browser

## Supported url parameters
- free (free text search)
- model1 (first model id)
- model2 (second model id)
- location1 (location id)
- debug (display query URI and PHP array for debugging purposes)

## Example
 https://your_web_server/trail.php?model1=1234567890&location1=12345&free=freetext&debug
