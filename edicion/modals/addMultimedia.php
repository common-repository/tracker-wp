<div class="modal wp adapter up1" id="mAddMulti" onclick="desactivarModal('mAddMulti')">
    <div class="noscrollx" onclick="cancelClose()">
        <div class="tkwp_botones center column">
            <button class="info" onclick="addMultimedia(1)">Foto</button>
            <button class="info" onclick="addMultimedia(2)">Video</button>
            <button class="info" onclick="addMultimedia(3)">Sonido</button>
            <button class="info" onclick="addMultimedia(4)">Archivo</button>
            <button class="info" onclick="addMultimedia(5)">Texto o Publicacion</button>
            <button class="info" onclick="addMultimedia(6)">Enlace</button>
        </div>
        <p style="text-align:center;">La longitud de audios, imagenes o videos debe ser igual o inferior a: <span style="color:red">
        <?php
        echo (ini_get('upload_max_filesize'));
        //post_max_size
        ?>
        </span></p>
        <button class="buttonClose" onclick="desactivarModal('mAddMulti')">Close</button>
    </div>
</div>