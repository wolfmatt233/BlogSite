<p>
    <?php
    $error = $_GET['e'];
    switch ($error) {
        case "db":
            echo "There was an error with the database, try again later.";
            break;
        case "404":
            echo "Error 404: Page not found.";
            break;
    }
    ?>
</p>