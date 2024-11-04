<?php
include 'models/functions.php';
?>

<!DOCTYPE html>
<html>

<?= template_header('Estrutura'); ?>


<!-- product section -->
<section class="product_section layout_padding">
    <div style="padding-top: 50px; padding-bottom: 30px;">
        <div class="container">
            <div class="heading_container3">

                <h3 style="font-size: 33px; text-transform: uppercase;">
                    <?= change_lang("org-struct-page-heading") ?>
                </h3>

                <div class="flex-container mobile_reverse">
                    <div class="flex-left">
                        <figure class="imgfigura">
                            <img class="imgestrutura w-100" style="max-width:330px;" src="./assets/images/estrutura.jpg" alt="Boat">
                            <figcaption class="imgs"></figcaption>
                        </figure>
                    </div>
                    <div class="flex-right">
                        <?= change_lang("org-struct-page-description") ?><br><br>

                        <b><?= change_lang("org-struct-page-director-tag") ?></b><br><?= change_lang("director") ?><br><br>

                        <b><?= change_lang("org-struct-page-deputy-director-tag") ?></b><br><?= change_lang("deputy-director") ?><br><br>

                        <b><?= change_lang("org-struct-page-executive-secretary-tag") ?></b><br><?= change_lang("executive-secretary") ?><br><br>

                        <b><?= change_lang("org-struct-page-board-tag") ?><br> </b><?= change_lang("board-composed") ?><br>
                        <?= change_lang("board-member1") ?><br>
                        <?= change_lang("board-member2") ?><br>
                        <?= change_lang("board-member3") ?><br>
                        <?= change_lang("board-member4") ?><br>
                        <?= change_lang("board-member5") ?><br><br>

                        <b><?= change_lang("org-struct-page-scinetific-conucil-tag") ?><br> </b><?= change_lang("all-integrated-members") ?><br><br>
<!--
                        <b><?= change_lang("org-struct-page-advisory-council-tag") ?><br>
                        </b><?= change_lang("advisory-council-one") ?><br>
                        <?= change_lang("advisory-council-two") ?><br>
                        <?= change_lang("advisory-council-three") ?><br>
                        <?= change_lang("advisory-council-four") ?><br>
                        <?= change_lang("advisory-council-five") ?><br>
                        <?= change_lang("advisory-council-six") ?><br><br>
-->
                        <b><?= change_lang("org-struct-page-advisory-council-tag") ?><br>
                        </b>
                        <section class="product_section layout_padding">
                            <div style="padding-top: 20px;">
                                <div class="container">
                                    <div class="row justify-content-center mt-3">
                                        <div  class="ml-5 imgList">
                                            <a href="consAnaCalvoManuel.php">
                                                <div  class="image_default">
                                                    <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/AnaCalvoManuel.png" alt="">
                                                    <div class="imgText justify-content-center m-auto">Ana María Calvo Manuel</div>
                                                </div>
                                            </a> 
                                        </div>
                                        <div  class="ml-5 imgList">
                                            <a href="consChaoGejin.php">
                                                <div  class="image_default">
                                                    <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/ChaoGejin.png" alt="">
                                                    <div class="imgText justify-content-center m-auto">Chao Gejin</div>
                                                </div>
                                            </a> 
                                        </div>
                                        <div  class="ml-5 imgList">
                                            <a href="consJoseJulioGarciaArranz.php">
                                                <div  class="image_default">
                                                    <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/JoseJulioGarciaArranz.png" alt="">
                                                    <div class="imgText justify-content-center m-auto">José Julio García Arranz</div>
                                                </div>
                                            </a> 
                                        </div>
                                        <div  class="ml-5 imgList">
                                            <a href="consLaurentTissot.php">
                                                <div  class="image_default">
                                                    <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/LaurentTissot.png" alt="">
                                                    <div class="imgText justify-content-center m-auto">Laurent Tissot</div>
                                                </div>
                                            </a> 
                                        </div>
                                        <div  class="ml-5 imgList">
                                            <a href="consMariaFilomenaGuerra.php">
                                                <div  class="image_default">
                                                    <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/MariaFilomenaGuerra.png" alt="">
                                                    <div class="imgText justify-content-center m-auto">Maria Filomena Guerra</div>
                                                </div>
                                            </a> 
                                        </div>
                                        <div  class="ml-5 imgList">
                                            <a href="consZoltanSomhegyi.php">
                                                <div  class="image_default">
                                                    <img class="centrare" style="object-fit: cover; width:225px; height:280px;" src="./assets/images/ZoltanSomhegyi.png" alt="">
                                                    <div class="imgText justify-content-center m-auto">Zoltán Somhegyi</div>
                                                </div>
                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>

                <!--                 <div class="flex-container">
                    
                    <div class="flex-left2">
                        "tempor pulvinar. Vivamus ultrices egestas posuere. Integer magna orci, vestibulum."
                    </div>

                    <div class="flex-right">
                        luctus. Maecenas et quam rutrum, dignissim orci sodales, eleifend elit. Praesent viverra odio at volutpat aliquam. Aliquam vitae libero molestie,
                        laoreet tellus non, pretium orci. Maximus lacus sed, aliquet ex. Integer faucibus ante et magna finibus, ac consequat sapien commodo.
                        Aenean a condimentum diam. Sed facilisis felis neque, a volutpat libero euismod ut. Donec pellentesque metus at enim tempor fringilla.
                        Morbi dictum mauris sem, in commodo magna elementum eu. Uis ornare, ex ac rhoncus dictum, magna mauris tincidunt dolor
                        ed odio lacus, bibendum sed leo fringilla, vehicula finibus sem. Fusce urna sem, vehicula a accumsan sed, molestie id mi.
                        Donec tempus odio nec nisi euismod, ac volutpat purus laoreet. Luctus eu liber a fermentum. Sed pretium turpis enim.
                    </div>

                </div> -->


            </div>
        </div>
    </div>
</section>
<!-- end product section -->

<?= template_footer(); ?>

</body>

</html>