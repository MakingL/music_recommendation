<?php
    function _add_track_url($song_id, $song_url, $type,
        $md5, $size) {
            // echo "function";
            include("_connectDB.php");
            $sql = "SELECT song_id
                    FROM song_url
                    WHERE song_id='$song_id'";
            $res = $conn->query($sql);
            $row = $res->fetch_assoc();
            if ($row == null) {
                $sql = "INSERT INTO song_url (song_id, song_url, type, md5, size)
                        VALUES ('$song_id', '$song_url', '$type', '$md5', '$size')";

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

