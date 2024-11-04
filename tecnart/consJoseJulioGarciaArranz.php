<?php

include 'models/functions.php';

?>

<?= template_header('José Julio García Arranz'); ?>

<!-- product section -->
<section>
    <div class="totall">
        <div class="barraesquerda">
            <h3 class="heading_h3" style="font-size: 38px; margin-bottom: 20px; padding-top: 60px; padding-right: 10px; padding-left: 60px; word-wrap: break-word;">
            José Julio García Arranz
            </h3>
            <div class="canvasEmail" style="height:150px; padding-right: 10px;">

                <div class="emailScroll">
                    <canvas id="canvas"></canvas>
                    <script>
                        const ratio = Math.ceil(window.devicePixelRatio);
                        const canvas = document.getElementById("canvas");
                        const txt = "";
                        const context = canvas.getContext("2d");
                        context.font = "15px 'Montserrat', sans-serif";

                        width = context.measureText(txt).width + 5
                        height = canvas.offsetHeight

                        canvas.width = width * ratio;
                        canvas.height = height * ratio;
                        canvas.style.width = `${width}px`;
                        canvas.style.height = `${height}px`;

                        context.font = "15px 'Montserrat', sans-serif";
                        context.fillStyle = "#060633";
                        context.setTransform(ratio, 0, 0, ratio, 0, 0);
                        context.fillText(txt, 0, 20);
                    </script>
                </div>
            </div>



          
        </div>
        <div id="resto" class="infoCorpo">
            <img style="object-fit: cover; width:255px; height:310px; padding-left: 50px; padding-top: 50px" src="./assets/images/JoseJulioGarciaArranz.png" alt="">

            <h3 class="heading_h3" style="font-size: 30px; margin-bottom: 20px; padding-top: 30px; padding-right: 10px; padding-left: 50px;">
                <?= change_lang("about-tab-title-class") ?>
            </h3>

            <div class="textInfo" style="padding-bottom: 80px;">
                <?= change_lang("about-JoseJulioGarciaArranz") ?>
            </div>

        </div>

       
    </div>


</section>
<!-- end product section -->

<?= template_footer(); ?>

</body>

</html>