<?php
    function _get_song_url($song_id) {
            include("_connectDB.php");
            $sql = "SELECT *
                    FROM song_url
                    WHERE song_id='$song_id'";
            $res = $conn->query($sql);

            $ret = array();
            while($row = $res->fetch_assoc()) {
                // 得到的 url 只可能是一条
                // array_push($ret, $row);
                $ret['song_id'] = $row['song_id'];
                $ret['song_url'] = $row['song_url'];
                $ret['type'] = $row['type'];
                $ret['md5'] = $row['md5'];
                $ret['size'] = $row['size'];
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

