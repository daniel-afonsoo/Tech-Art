<?php
include 'models/functions.php';
?>


<?=redirectPageLanguage("financiamento.php","funding.php") ?>


<!DOCTYPE html>
<html>

<?= template_header('Financiamento'); ?>


<!-- product section -->
<section class="product_section layout_padding">
    <div style="padding-top: 50px; padding-bottom: 30px;">
        <div class="container">
            <div class="heading_container3">
                <h3 style="text-transform: uppercase;">
                    <?= change_lang("funding-title") ?>
                </h3>
                <div style="padding-top: 30px;">
                    <p><?= change_lang("funding-p1") ?></p>
                    <p style="text-transform: uppercase;"><strong><?= change_lang("funding-title") ?></strong></p>
                    <!-- Table 1 -->
                    <!-- Table 1 -->
                    <div class="table-responsive">
                        <table class="table table-bordered responsive-table" style="max-width: 100%;">
                            <tbody>
                                <tr>
                                    <td colspan="2" rowspan="2">
                                        <p><strong><?= change_lang("funding-table1-project") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>UID/05488/2020</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><?= change_lang("funding-table1-project-name") ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p><strong><?= change_lang("funding-table1-investigator") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>Doutora Hermínia Sol</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p><strong><?= change_lang("funding-table1-promoter") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>IP Tomar</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p><strong><?= change_lang("funding-table1-date-celebration") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>14/05/2020</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">
                                        <p><strong><?= change_lang("funding-table1-execution-period") ?></strong></p>
                                    </td>
                                    <td>
                                        <p><strong><?= change_lang("funding-table1-start-date") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>01/01/2020</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong><?= change_lang("funding-table1-end-date") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>31/12/2024</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p><strong><?= change_lang("funding-table1-total-investment") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>691 750,00€</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <p><strong><?= change_lang("funding-table1-funding") ?></strong></p>
                                    </td>
                                    <td>
                                        <p>691 750,00€</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p>&nbsp;</p>
                    <p><?= change_lang("funding-p2") ?></p>
                    <p style="text-transform: uppercase;"><strong><?= change_lang("funding-title-2") ?></strong>&nbsp;</p>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="height: 102px;">
                            <tbody>
                                <tr>
                                    <td style="width: 170px; height: 10px;" width="170">
                                        <p><strong><?= change_lang("funding-tables-universal-code") ?></strong></p>
                                    </td>
                                    <td width="217">
                                        <p>UIDB/05488/2020</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="170">
                                        <p><strong><?= change_lang("funding-tables-funding") ?></strong></p>
                                    </td>
                                    <td width="217">
                                        <p>353 750,00€</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="170">
                                        <p><strong><?= change_lang("funding-tables-execution-period") ?></strong></p>
                                    </td>
                                    <td width="217">
                                        <p>01/01/2020 - 31/12/2024</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paragraphs -->

                    <!-- Table 3 -->
                    <p>&nbsp;</p>
                    <p style="text-transform: uppercase;"><strong><?= change_lang("funding-title-3") ?></strong></p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="170">
                                        <p><strong><?= change_lang("funding-tables-universal-code") ?></strong></p>
                                    </td>
                                    <td width="217">
                                        <p>UIDP/05488/2020</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="170">
                                        <p><strong><?= change_lang("funding-tables-funding") ?></strong></p>
                                    </td>
                                    <td width="217">
                                        <p>338 000,00 €</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="170">
                                        <p><strong><?= change_lang("funding-tables-execution-period") ?></strong></p>
                                    </td>
                                    <td width="217">
                                        <p>01/01/2020 - 31/12/2024</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= template_footer(); ?>

</body>

</html>