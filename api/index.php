<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piekności</title>
    <link rel="stylesheet" href="../resources/css/upload.css">
    <link rel="stylesheet" href="../resources/css/api.css">
    <link rel="icon" type="image/ico" href="favicon.ico">
</head>

<body>
    <div class="center">
        <table>
            <?php
                for ($i = 0; $i < 8; $i++) {
                    echo "<tr id='row{$i}'>";
                    for ($n = 0; $n < 8; $n++) {
                        echo "<td id='c{$i}{$n}' onclick='setPixel(\"c{$i}{$n}\")'></td>";
                    }
                    echo "</tr>";
                }
            ?>
            <tr>
                <td colspan="8"><input id='sendText' type='textarea'></td>
            </tr>
            <tr>
                <td colspan="8">
                    <button onclick="save()">Send</button>
                </td>
            </tr>
        </table>
    </div>
    

    
    
</body>

<script>

    data = [
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
        [0,0,0,0,0,0,0,0],
    ];

    function setPixel(id) 
    {
        if (document.getElementById(id).style.backgroundColor == 'black') {
            document.getElementById(id).style.backgroundColor = 'transparent'
            data[id[1]][id[2]] = 0;
        }
        else {
            document.getElementById(id).style.backgroundColor = 'black'
            data[id[1]][id[2]] = 1;
        }
        console.log(data);
    }

    function save() {
        if (document.getElementById('sendText').value != "") {
            return window.location.href = "save.php?mode='1'"
            + "&r0=" + document.getElementById('sendText').value;
        }
        else {
            window.location.href = "save.php?mode='0'"
            + "&r0=" + data[0] 
            + "&r1=" + data[1] 
            + "&r2=" + data[2] 
            + "&r3=" + data[3]
            + "&r4=" + data[4]
            + "&r5=" + data[5]
            + "&r6=" + data[6]
            + "&r7=" + data[7];
        }

        
    }

</script>

</html>