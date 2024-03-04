<!-- Footer Start -->
<footer class="footer">
    <a href="http://www.imaginatecperu.com" target="_blank">
        <?php 
        $anio1 = "2015";
        $aniof = date("Y");
        if($anio1 != $aniof)
            $anio1 = $anio1 . " - " . $aniof;
        echo $anio1;
        ?> © Imagina Technologies
    </a>
</footer>
<!-- Footer Ends -->