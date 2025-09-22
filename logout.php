<?php
// logout.php
// Logout and redirect with JS
session_start();
session_destroy();
?>
<script>
    window.location.href = 'index.php';
</script>
