<?php
    
    //$host = "b2app-db.cvqgkpa50edh.sa-east-1.rds.amazonaws.com";
    $host = "b2app-db-read.cvqgkpa50edh.sa-east-1.rds.amazonaws.com";
    $db   = "b2app";
    $user = "b2";
    $pass = "DHRKLcE7G9lTtVbM4YW5fPu67Ww5m6";

    $conn = new mysqli($host, $user, $pass, $db);
    mysqli_set_charset($conn, "utf8");

    if (!$conn) {
        die("<br/><br/> Não há conexão ou há algum bloqueio na conexão com o banco de dados. <br/><br/>");
    }

    $sql_01 = "select 
                    H.ViewTotals,
                    I.CurtidasTotals,
                    J.CometarioTotals
                from 
                    (select count(id) AS ViewTotals from view ) as H,
                    (select count(id) AS CurtidasTotals from `like`) as I,
                    (select count(id) AS CometarioTotals from comment) as J";

    $result_01 = mysqli_query($conn, $sql_01);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
    <meta http-equiv="refresh" content="10">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.quicksearch/2.3.1/jquery.quicksearch.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="shortcut icon" type="image/x-icon" href="Work.ico">
    <title>Contador</title>

    <style>
       h1 {
            display:            block;
            font:               600 1.5em/1 \'Open Sans\', sans-serif;
            text-align:         center;
            letter-spacing:     .2em;
            line-height:        1.6;
            top:                15px;
            margin:             auto;
            width:              50%;
            padding:            10px;
            background-color:   #0d5b5d;
            color:              white;
        }
        span {
            font-family:        "Open Sans";
            font-size:          14px;
            z-index:            10;
        }
        span.mySpan {
            padding:            10px;
            background-color:   #0d5b5d;
            display:            block;
            margin:             auto;
            width:              50%;
            height:             auto;
            bottom:             15;
            word-wrap:          break-word;
            min-height:         160px;
            color:              white;
        }
        .allContent {
            width:              98%;
            margin:             auto;
            margin-top:         20px;
            overflow:           scroll;
            overflow:           auto;
            background:         rgb(252, 252, 252);
            border:             3px solid rgb(245, 245, 245, 0.5);
            border-radius:      5px;
        }
        table {
            font-family:        "Trebuchet MS", Arial, Helvetica, sans-serif;
            border-collapse:    collapse;
            width:              100%;
            margin:             auto;
            padding:            15px;
        }
        .allContent::-webkit-scrollbar {
            width: 15px;
        }
        .allContent::-webkit-scrollbar-track {
            box-shadow:     inset 0 0 5px grey; 
            border-radius:  10px;
        }
        .allContent::-webkit-scrollbar-thumb {
            background:     #cccccc; 
            border-radius:  10px;
        }
        .allContent::-webkit-scrollbar-thumb:hover {
            background:     #dddddd; 
        }
        td, th {
            border:     1px solid #ddd;
            padding:    8px;
            font-size:  14px;
        }
        tr:nth-child(even){background-color: #f2f2f2;}
        tr:hover {background-color: #ddd;}
        th {
            padding-top:        12px;
            padding-bottom:     12px;
            text-align:         left;
            background-color:   #2fb8e9;
            color:              white;
        }
        th:hover {
            cursor:             pointer;
            background-color:   #002b6a;
            color:              white;
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before {

    </style>
</head>
<body>

    <div style="
            background-image:   url(images/patternfail.jpg);
            background-repeat:  repeat;
            width:              100%;
            height:             100%;
            position:           absolute;
            top:                0; 
            left:               0;
            z-index:            -10;
            opacity:            0.1;">
        </div>
        <div class="form-group input-group" style="width: 98%; margin:auto; margin-top: 50px;">
            <span class="input-group-addon">
                <i class="glyphicon glyphicon-search"></i>
            </span>
            <input name="consulta" id="txt_consulta" placeholder="Consultar" type="text" class="form-control">
        </div>
        <div class="allContent">
            <div class="image">
                <center><a href="https://docs.google.com/spreadsheets/d/1otoa2qt3V4FcNeeiJywSxMqeeqinoxBwX0MY37MptNM/edit?ts=5d41d69f#gid=0" target="_blank">
                    <img src="planilha.png" width="30" height="30">
                    TRIAL LEADS sheets
                </a></center>
            </div>
            <table>
                
            </table>
            <table id="minhasCurtidas" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                    <tr style="pointer-events: none;">
                        <th colspan="3" style="background-color: #002b6a;"><center>Interactions</center></th>
                    </tr>
                    <tr height="30px">
                        <th onclick="sortTable(10)"><center><img src='viewsbranco.png' height='20' width='20'></center></th>
                        <th onclick="sortTable(11)"><center><img src='likebranco.png' height='20' width='20'></center></th>
                        <th onclick="sortTable(12)"><center><img src='commentbranco.png' height='20' width='20'></center></th>
                    </tr>
                </thead>
                <tbody>
                     <?php

                        if ($result_01){

                            $row_01 = $result_01->fetch_assoc();

                            echo "<tr height='30px' style='pointer-events: none;'>
                                    <th onclick='sortTable(9)' style='background-color: #002b6a;'><center>".$row_01["ViewTotals"]."</center></th>
                                    <th onclick='sortTable(10)' style='background-color: #002b6a;'><center>".$row_01["CurtidasTotals"]."</center></th>
                                    <th onclick='sortTable(11)' style='background-color: #002b6a;'><center>".$row_01["CometarioTotals"]."</center></th>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
            </div>
            <div class="myBottom"></div>
            <script>
                
                $("input#txt_consulta").quicksearch("table#minhasCurtidas tbody tr");
                
                $("th").click(function(){
                var table = $(this).parents("table").eq(0)
                var rows = table.find("tr:gt(1)").toArray().sort(comparer($(this).index()))
                this.asc = !this.asc
                
                if (!this.asc){rows = rows.reverse()}
                    for (var i = 0; i < rows.length; i++){
                        table.append(rows[i])}
                })
                
                function comparer(index) {
                    return function(a, b) {
                        var valA = getCellValue(a, index),
                        valB = getCellValue(b, index)
                        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
                    }
                }
                
                function getCellValue(row, index){
                    return $(row).children("td").eq(index).text()
                }

            </script>
</body>
</html>
<?php
mysqli_free_result($result_01);
?>