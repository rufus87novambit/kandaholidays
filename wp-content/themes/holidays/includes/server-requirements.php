<?php

$statuses = array(
    'SoapClient' => class_exists( 'SoapClient' )
);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test requirements</title>
    </head>

    <body>
        <style>
            body {
                text-align: center;
            }
            table, td, th {
                border:1px solid #000;
                border-collapse: collapse;
            }
            table {
                width: 100%;
            }
            td, th {
                padding: 10px 20px;
                width: 50%;
            }
            .success {
                background: green;
            }
            .error {
                background: red;
            }
        </style>
        <div style="width:700px; margin: 0 auto; text-align: center;">
            <h1>Server status</h1>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>Requirement</th>
                    <th>Status</th>
                </tr>
                <?php foreach( $statuses as $name => $status ) { ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td class="<?php echo $status ? 'success' : 'error'; ?>"><?php echo $status ? 'Ok' : 'Fail'; ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </body>
</html>

<?php

die;

?>