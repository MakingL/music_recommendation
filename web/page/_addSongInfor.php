<?php
    function _add_song_information($song_id, $song_name, $artist_name,
        $album_id, $album_name, $album_picture) {
            // echo "function";
            include("_connectDB.php");
            $sql = "SELECT song_id
                    FROM song_information
                    WHERE song_id='$song_id'";
            $res = $conn->query($sql);
            $row = $res->fetch_assoc();
            if ($row == null) {
                $sql = "INSERT INTO song_information (song_id, song_name, artist_name, album_id, album_name, album_picture)
                        VALUES ('$song_id', '$song_name', '$artist_name', '$album_id', '$album_name', '$album_picture')";

                $conn->query($sql);
                // if ($conn->query($sql) === TRUE) {
                //     echo "New record created successfully </br>";
                // } else {
                //     echo "Error: " . $sql . "<br>" . $conn->error;
                // }

            }
            $conn->close();
    }
?>

