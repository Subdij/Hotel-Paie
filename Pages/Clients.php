<h2>Clients</h2>
<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Check-In Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Replace this with a query to fetch client data from your database
        $clients = [
            ['John Doe', 'john@example.com', '123-456-7890', '2024-12-01'],
            ['Jane Smith', 'jane@example.com', '987-654-3210', '2024-12-03'],
        ];
        foreach ($clients as $client) {
            echo "<tr>
                    <td>{$client[0]}</td>
                    <td>{$client[1]}</td>
                    <td>{$client[2]}</td>
                    <td>{$client[3]}</td>
                  </tr>";
        }
        ?>
    </tbody>
</table>
