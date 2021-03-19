<?php
include 'fonctionsBD.php';
$liste_smarpthone = getSmartphoneVente(false);
echo json_encode($liste_smarpthone)
?>
