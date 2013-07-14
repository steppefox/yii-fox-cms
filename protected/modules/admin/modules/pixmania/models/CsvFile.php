<?php
/**
 * Logiciel : exemple d'utilisation de HTML2PDF
 * 
 * Convertisseur HTML => PDF
 * Distribué sous la licence LGPL. 
 *
 * @author		Laurent MINGUET <webmaster@html2pdf.fr>
 * 
 * isset($_GET['vuehtml']) n'est pas obligatoire
 * il permet juste d'afficher le résultat au format HTML
 * si le paramètre 'vuehtml' est passé en paramètre _GET
 */
 	// récupération du contenu HTML
 	ob_start();
 	$num = 'CMD01-'.date('ymd');
 	$nom = 'DUPONT Alphonse';
 	$date = '01/01/2010';
?>
<style type="text/css">
<!--
	div.zone { border: none; border-radius: 6mm; background: #FFFFFF; border-collapse: collapse; padding:3mm; font-size: 2.7mm;}
	h1 { padding: 0; margin: 0; color: #DD0000; font-size: 7mm; }
	h2 { padding: 0; margin: 0; color: #222222; font-size: 5mm; position: relative; }
-->
</style>
<page format="100x200" orientation="L" backcolor="#AAAACC" style="font: arial;">
	<div style="rotate: 90; position: absolute; width: 100mm; height: 4mm; left: 195mm; top: 0; font-style: italic; font-weight: normal; text-align: center; font-size: 2.5mm;">
		Ceci est votre e-ticket à présenter au contrôle d'accès -
		billet généré par <a href="http://html2pdf.fr/" style="color: #222222; text-decoration: none;">html2pdf</a>
	</div>
	<table style="width: 99%;border: none;" cellspacing="4mm" cellpadding="0">
		<tr>
			<td colspan="2" style="width: 100%">
				<div class="zone" style="height: 34mm;position: relative;font-size: 5mm;">
					<div style="position: absolute; right: 3mm; top: 3mm; text-align: right; font-size: 4mm; ">
						<b><?php echo $nom; ?></b><br>
					</div>
					<div style="position: absolute; right: 3mm; bottom: 3mm; text-align: right; font-