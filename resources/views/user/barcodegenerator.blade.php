<html lang="pt-br">
<head>
    <style>
        * {
            color:#7F7F7F;
            font-family:Arial,sans-serif;
            font-size:12px;
            font-weight:normal;
        }
        #config{
            overflow: auto;
            margin-bottom: 10px;
        }
        .config{
            float: left;
            width: 200px;
            height: 250px;
            border: 1px solid #000;
            margin-left: 10px;
        }
        .config .title{
            font-weight: bold;
            text-align: center;
        }
        .config .barcode2D,
        #miscCanvas{
            display: none;
        }
        #submit{
            clear: both;
        }
        #barcodeTarget,
        #canvasTarget{
            margin-top: 20px;
        }
    </style>

    <script type="text/javascript" src="{{asset("js/jquery-1.3.2.min.js")}}"></script>
    <script type="text/javascript" src="{{asset("js/jquery-barcode.js")}}"></script>
    <script type="text/javascript">

        function generateBarcode(){
            var value = $("#barcodeValue").val();
            var btype = $("input[name=btype]:checked").val();
            var renderer = $("input[name=renderer]:checked").val();

            var quietZone = false;
            if ($("#quietzone").is(':checked') || $("#quietzone").attr('checked')){
                quietZone = true;
            }

            var settings = {
                output:renderer,
                bgColor: $("#bgColor").val(),
                color: $("#color").val(),
                barWidth: $("#barWidth").val(),
                barHeight: $("#barHeight").val(),
                moduleSize: $("#moduleSize").val(),
                posX: $("#posX").val(),
                posY: $("#posY").val(),
                addQuietZone: $("#quietZoneSize").val()
            };
            if ($("#rectangular").is(':checked') || $("#rectangular").attr('checked')){
                value = {code:value, rect: true};
            }
            $("#canvasTarget").hide();
            $("#barcodeTarget").html("").show().barcode(value, btype, settings);

        }

        function showConfig1D(){
            $('.config .barcode1D').show();
            $('.config .barcode2D').hide();
        }

        function showConfig2D(){
            $('.config .barcode1D').hide();
            $('.config .barcode2D').show();
        }

        $(function(){
            $('input[name=btype]').click(function(){
                if ($(this).attr('id') == 'datamatrix') showConfig2D(); else showConfig1D();
            });
            generateBarcode();
        });

    </script>
</head>
<body>
<div id="generator">
    Please fill in the code : <input type="text" id="barcodeValue" value="12345670">
    <div id="config">
        <div class="config">
            <div class="title">Type</div>
            <input type="radio" name="btype" id="code39" value="code39"><label for="code39">code 39</label><br />
            <input type="radio" name="btype" id="datamatrix" value="datamatrix"><label for="datamatrix">Data Matrix</label><br /><br />
        </div>

        <div class="config">
            <div class="title">Misc</div>
            Background : <input type="text" id="bgColor" value="#FFFFFF" size="7"><br />
            "1" Bars : <input type="text" id="color" value="#000000" size="7"><br />
            <div class="barcode1D">
                bar width: <input type="text" id="barWidth" value="1" size="3"><br />
                bar height: <input type="text" id="barHeight" value="50" size="3"><br />
            </div>
        </div>

        <div class="config">
            <div class="title">Format</div>
            <input type="radio" id="css" name="renderer" value="css" checked="checked"><label for="css">CSS</label><br />
            <input type="radio" id="bmp" name="renderer" value="bmp"><label for="bmp">BMP (not usable in IE)</label><br />
        </div>
    </div>

    <div id="submit">
        <input type="button" onclick="generateBarcode();" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Generate the barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;">
    </div>

</div>

<div id="barcodeTarget" class="barcodeTarget"></div>
<canvas id="canvasTarget" width="150" height="150"></canvas>

</body>
</html>

