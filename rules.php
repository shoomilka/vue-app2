 <?php
    $sql = "SELECT * FROM `rules`;";

    $rules = [];
    if ($result = $conn->query($sql)) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            if($row["type"] == 'minimum') $minimum = $row["position"];
            elseif($row["type"] == 'maximum') $maximum = $row["position"];
            else{
                array_push($rules, ['key' => $i,
                                    'type' => $row["type"],
                                    'position' => $row["position"],
                                    'symbol' => $row["symbol"]]);
                $i++;
            }
        }
        $result->free();
    }