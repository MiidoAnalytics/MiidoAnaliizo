<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content=""><meta name="author" content="">
<!-- Estilo de los Tooltip-->
<link rel="stylesheet" type="text/css" href="../../../sitemedia/css/tooltipster.css" /> 
<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<!-- Bootstrap Core CSS -->
<link href="../../../sitemedia/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="../../../sitemedia/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
<!-- DataTables CSS -->
<link href="../../../sitemedia/css/plugins/dataTables.bootstrap.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="../../../sitemedia/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="../../../sitemedia/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!-- Morris Charts CSS -->
<link href="../../../sitemedia/css/plugins/morris.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>    
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js">
</script>    
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script><![endif]-->
<script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!--<script type="text/javascript">    // Specify the normal table row background color    //   and the background color for when the mouse     //   hovers over the table row.    var TableBackgroundNormalColor = "#ffffff";    var TableBackgroundMouseoverColor = "#ddf3de";    // These two functions need no customization.    function ChangeBackgroundColor(row) {        row.style.backgroundColor = TableBackgroundMouseoverColor;    }    function RestoreBackgroundColor(row) {        row.style.backgroundColor = TableBackgroundNormalColor;    }</script>carga los encuestados por municipio<script>    function showUser(str) {        if (str == "") {            document.getElementById("txtHint").innerHTML = "";            return;        } else {            if (window.XMLHttpRequest) {                // code for IE7+, Firefox, Chrome, Opera, Safari                xmlhttp = new XMLHttpRequest();            } else {                // code for IE6, IE5                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");            }            xmlhttp.onreadystatechange = function () {                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {                    document.getElementById("txtHint").innerHTML = xmlhttp.responseText;                }            }            xmlhttp.open("GET", "getuser.php?q=" + str, true);            xmlhttp.send();        }    }</script>-->