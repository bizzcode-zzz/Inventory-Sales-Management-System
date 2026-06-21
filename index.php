<!DOCTYPE html>
<html lang="tl">
<head>
    <meta charset="UTF-8">
    <title>Aking Unang PHP at XAMPP Script</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding-top: 50px; }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #ff9900; color: white; border: none; border-radius: 5px; }
        #priceText { font-size: 24px; font-weight: bold; color: green; margin-top: 20px; }
        #dogeImage { margin-top: 20px; display: none; width: 200px; border-radius: 10px; }
    </style>
</head>
<body>

    <?php
    // Galing sa PHP: Dito natin pwedeng i-set ang presyo na galing sa database o server
    $kasalukuyang_presyo = "$0.084";
    $larawan_url = "https://unsplash.com";
    ?>

    <h2>XAMPP PHP Server Test</h2>
    <p>Pindutin ang button para sa iyong ideya.</p>
    
    <!-- Ang Button -->
    <button onclick="simulanAngMagic()">Pindutin Dito</button>

    <!-- Dito isasaksak ng JavaScript ang data na galing sa PHP variable sa itaas -->
    <div id="priceText"></div>

    <!-- Ang Larawan -->
    <img id="dogeImage" src="<?php echo $larawan_url; ?>" alt="Doge">

    <!-- JAVASCRIPT: Siya pa rin ang namamahala sa timer (3 seconds) sa screen ng user -->
    <script>
        function simulanAngMagic() {
            // Kinukuha natin ang data mula sa PHP variable gamit ang echo
            var presyoMulaPHP = "<?php echo $kasalukuyang_presyo; ?>";
            
            // 1. Ipakita agad ang presyo
            document.getElementById("priceText").innerHTML = "Presyo mula sa PHP Server: " + presyoMulaPHP;

            // 2. Mag-antay ng 3 segundo (3000ms) bago ipakita ang larawan
            setTimeout(function() {
                document.getElementById("dogeImage").style.display = "block";
            }, 3000); 
        }
    </script>

</body>
</html>
