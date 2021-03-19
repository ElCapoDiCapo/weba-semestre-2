<?php
$title = "Catalogue";
include('fonctionsBD.php');
include('header2.php');
session_start();
$mail = $_SESSION['user'];
$i = 0;
?>

<div id="listeTelehones">


    <?php foreach (getSmartphoneVente(true) as $smartphone): ?>
        <div class='cartouche'>

            <div class='cardC'>
                <div class='data'>
                    <?= $smartphone["MODEL_TYPE_DE_SMARTPHONE"] ?>
                </div>
                <div class='pict'>
                    <a href="detailProduit.php?id=<?php echo $smartphone['IDSMARTPHONE']; ?>"><img src="<?php echo "./imgCatalogue/".$smartphone['IMG_SMARTPHONE']; ?>" alt="<<?php echo $smartphone['MODEL_TYPE_DE_SMARTPHONE']; ?>"></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<button id="boutton" type="button">voir plus !</button>


<?php include('footer.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="afficherSmartphones.js"></script>