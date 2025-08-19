<?php
// Assuming you have a database connection named $conn

// Query to select products with quantity less than 10
$sql = "SELECT codigo, nombre, stock_actual, stock_minimo FROM producto WHERE stock_actual <= stock_minimo";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['codigo'] . "</td>";
        echo "<td>" . $row['nombre'] . "</td>";
        $quantityColor = ($row['stock_actual'] <= $row['stock_minimo']) ? 'color: red;' : '';
        echo "<td style='$quantityColor'>" . $row['stock_actual'] . "</td>";
        echo "</tr>";
    }
} else {
    echo '
    <tr>
        <td colspan="5"><center>Record Not Found</center></td>
    </tr>';
}

// Close the database connection
mysqli_close($conn);


?>