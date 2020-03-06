<?php
    function _get_song_information($song_id) {
            include("_connectDB.php");
            $sql = "SELECT *
                    FROM song_information
                    WHERE song_id='$song_id'";
            $res = $conn->query($sql);

            $ret = array();
            while($row = $res->fetch_assoc()) {
                // array_push($ret, $row);
                $ret['song_id'] = $row["song_id"];
                $ret['song_name'] = $row["song_name"];
                $ret['artist_name'] = $row["artist_name"];
                $ret['album_name'] = $row["album_name"];
                $ret['album_id'] = $row["album_id"];
                $ret['album_picture'] = $row["album_picture"];
            }

            if ($ret == null) {
                $ret['code'] = '500';
            } else {
                $ret['code'] = '200';
            }

            $conn->close();
            return json_encode($ret,
                    JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }
?>

