<?php
include('konekcija.php');

if (isset($_POST['key'])) {

    if ($_POST['key'] == 'login') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = mysqli_query($conn, "SELECT * FROM `korisnici` WHERE username = '$username' AND password = '$password'");

        if (mysqli_num_rows($query) == 0) {
            echo "ERROR";
        } else {
            echo "OK";
        }
    }

    if ($_POST['key'] == 'reg') {
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $query = mysqli_query($conn, "SELECT * FROM `korisnici` WHERE username = '$username' AND password = '$password'");

        if (mysqli_num_rows($query) == 0) {
            $sql = "INSERT INTO `korisnici` (`imePrezime`, `username`, `password`) VALUES ('$name', '$username', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo "Podaci sačuvani u našoj bazi!";
            } else {
                echo "Neuspešno čuvanje podataka u bazi.";
            }
        } else {
            echo 'Već postoji.';
        }
    }

    mysqli_close($conn);
}
?>