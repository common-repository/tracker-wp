<h2>Permisos</h2>
<p>Los permisos marcados son los que se recomiendan, si quiere poner o quitar alguno pulse en su cuadrado. </p>

<form id="formu1" class ="space" onsubmit="requestGenerica('formu1')" method="post" style="display:flex;justify-content: flex-start;  flex-wrap: wrap;">
   
    <div style="padding-left: 1em;padding-right: 1em;">  
        <h4>Permisos de edicion</h4>
        <input type="checkbox" id="admin" name="admin" value="true" checked>
        <label for="admin">Administradores</label><br>
        
        <input type="checkbox" id="editor" name="editor" value="true" checked>
        <label for="editor">Editores</label><br>
        
        <input type="checkbox" id="autor" name="autor" value="true" checked>
        <label for="autor">Autores</label><br>
    
        <input type="checkbox" id="colaborador" name="colaborador" value="true">
        <label for="colaborador">Colaboradores</label><br>
    </div>

    <div style="padding-left: 1em;padding-right: 1em;">
        <h4>Permisos de visualizacion</h4>
        <input type="checkbox" id="admin2" name="admin2" value="true" checked>
        <label for="admin2">Administradores</label><br>
        
        <input type="checkbox" id="editor2" name="editor2" value="true" checked>
        <label for="editor2">Editores</label><br>
        
        <input type="checkbox" id="autor2" name="autor2" value="true" checked>
        <label for="autor2">Autores</label><br>
    
        <input type="checkbox" id="colaborador2" name="colaborador2" value="true" checked>
        <label for="colaborador2">Colaboradores</label><br>

        <input type="checkbox" id="sus2" name="sus2" value="true" checked>
        <label for="sus2">Suscriptores</label><br>

        <input type="checkbox" id="inv2" name="inv2" value="true" checked>
        <label for="inv2">Invitados (No logeados)</label><br><br>
    </div>
    <input type="hidden" name="action" id="action" value="thanks">
    <div style="width:98vw;">
        <?php submit_button("Terminar Configuracion"); ?>
    </div>
   

</form>