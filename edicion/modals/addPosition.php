<div class="modal wp back up" id="mAddPosition" onclick="desactivarModal('mAddPosition')">
    <div onclick="cancelClose()">
        <div class="hori">
            <div class="izq">
                <button class="buttonClose" onclick="desactivarModal('mAddPosition')">Close</button>
                <h1>Editar posicion: <button id="deletePosition" class="tkwp_button danger" onclick="cargandoPost2('deleteposition','addPosition','deletePosition',reload,errorJson);">Borrar</button></h1>
                <form class="allForm line" id="addPosition" method="post">
                    <label for="namePosition">Nombre de la posicion:</label>
                    <input type="text" name="namePosition" id="namePosition" placeholder="Nombre de la position">
                    <label for="desPosition">Descripcion de la posicion:</label>
                    <textarea name="desPosition" id="desPosition" rows="10" cols="50"
                        placeholder="Descripcion de la position"></textarea>
                    <label for="latPosition">Latitud:</label>
                    <input type="text" name="latPosition" id="latPosition" placeholder="0.0000">
                    <label for="lonPosition">Longitud:</label>
                    <input type="text" name="lonPosition" id="lonPosition" placeholder="0.0000">
                    <label for="rutPosition">Ruta:</label>
                    <input type="text" name="rutPosition" id="rutPosition" placeholder="Better Shops">
                    <input type="hidden" name="action" id="action" value="createposition">
                    <input type="hidden" name="IDMapa" id="IDMapa" value="<?php echo $GLOBALS['MAPA']->getShortcode(); ?>">
                    <input type="hidden" id="idPosi" name = "idPosi" value="">
                    <div class="tkwp_botones">
                        <button id="createPButton" class="ok x" onclick="cargandoPost('addPosition','createPButton',reload,errorJson)">Crear Posicion</button>
                        <button class="danger x" onclick="desactivarModal('mAddPosition')">Cancelar</button>
                    </div>
                </form>
            </div>
            <div class="der">
                <h3>Elementos multimedia:</h3>
                <div class="tkwp_botones">
                    <button class="info x" onclick="activarModal('mAddMulti')"><span style="font-size:20px;"> + </span>Agregar Multimedia</button>
                </div>
                <div id="TablaMultimedias">
                    
                </div>
            </div>
        </div>
    </div>
</div>