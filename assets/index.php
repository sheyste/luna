<?php
// Send a 404 header
http_response_code(404);

// Optionally redirect to a custom 404 page
header("Location: /404");
exit();
?>
