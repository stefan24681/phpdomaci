<?php
include('konekcija.php');

session_start();

if (isset($_POST['key'])) {

    if ($_POST['key'] == 'ubaci') {

        $korisnik_id = $_SESSION['korisnik_id'];

        $proizvod_id = $_POST['proizvod_id'];
        $naziv = $_POST['naziv'];
        $godinaProizvodnje = $_POST['godina_proizvodnje'];
        $vrsta = $_POST['vrsta'];
        $cena = $_POST['cena'];

        $check = mysqli_query($conn, "SELECT * FROM prodaja WHERE proizvod_id = '$proizvod_id'");

        if (mysqli_num_rows($check) > 0) {
            echo "Isti proizvod " . $naziv . ", već postoji!";
        } else {

            $sql = "INSERT INTO `prodaja` (`proizvod_id`,`naziv`, `vrsta`, `godina_proizvodnje`,`cena`,`korisnik_id`) VALUES ('$proizvod_id','$naziv', '$vrsta', '$godinaProizvodnje', '$cena', '$korisnik_id')";

            if ($conn->query($sql) === TRUE) {
                echo "Ubacen novi proizvod!";
            } else {
                echo "Takav proizvod vec postoji!";
            }
        }
    }


    if ($_POST['key'] == 'ucitaj') {

        $korisnik_id = $_SESSION['korisnik_id'];

        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);
        $sort = $conn->real_escape_string($_POST['sort']);
        $sql = $conn->query("SELECT proizvod_id, naziv, godina_proizvodnje, vrsta, cena FROM prodaja WHERE korisnik_id = $korisnik_id ORDER BY $sort LIMIT $start, $limit");

        if ($sql->num_rows > 0) {
            $response = "";
            while ($data = $sql->fetch_array()) {

                $response .= '
                                <tr>
                                    <td id="proizvod_' . $data["proizvod_id"] . '">' . $data["proizvod_id"] . '</td>
                                    <td>' . $data["naziv"] . '</td>
                                    <td>' . $data["godina_proizvodnje"] . '</td>
                                    <td>' . $data["vrsta"] . '</td>
                                    <td>' . $data["cena"] . '</td>
                                    <td>
                                        <input type="button" onclick="izmeniPogledaj(' . $data["proizvod_id"] . ', \'izmeni\')" value="Izmeni" class="btn btn-primary">
                                        <input type="button" onclick="izmeniPogledaj(' . $data["proizvod_id"] . ', \'pogledaj\')" value="Pogledaj" class="btn btn-warning">
                                        <input type="button" onclick="izbrisi(' . $data["proizvod_id"] . ')" value="Izbriši" class="btn btn-danger">
                                    </td>
                                </tr>
                            ';
            }
            exit($response);
        } else
            exit('reachedMax');
    }



    if ($_POST['key'] == 'izbrisi') {
        $proizvod_id = $conn->real_escape_string($_POST['proizvod_id']);
        $conn->query("DELETE FROM prodaja WHERE proizvod_id='$proizvod_id'");
        exit('Proizvod obrisan!');
    }

    if ($_POST['key'] == 'uzmiPodatke') {
        $proizvod_id = $conn->real_escape_string($_POST['proizvod_id']);
        $sql = $conn->query("SELECT proizvod_id , naziv, vrsta, godina_proizvodnje, cena FROM prodaja WHERE proizvod_id = $proizvod_id");
        $data = $sql->fetch_array();
        $jsonArray = array(
            'proizvod_id' => $data['proizvod_id'],
            'naziv' => $data['naziv'],
            'vrsta' => $data['vrsta'],
            'godina_proizvodnje' => $data['godina_proizvodnje'],
            'cena' => $data['cena']
        );
        exit(json_encode($jsonArray));
    }


    if ($_POST['key'] == 'izmeni') {

        $trenutni_red = $conn->real_escape_string($_POST['proizvod_id']);

        $proizvod_id = $_POST['proizvod_id'];
        $naziv = $_POST['naziv'];
        $vrsta = $_POST['vrsta'];
        $godina_proizvodnje = $_POST['godina_proizvodnje'];
        $cena = $_POST['cena'];


        $sql = "UPDATE prodaja SET naziv='$naziv', vrsta='$vrsta', godina_proizvodnje='$godina_proizvodnje', cena='$cena' WHERE proizvod_id='$trenutni_red'";
        if ($conn->query($sql) === TRUE) {
            echo "Uspešno izmenjen proizvod!";
        } else {
            echo "Proizvod nije izmenjen!";
        }

    }
}

mysqli_close($conn);

?>