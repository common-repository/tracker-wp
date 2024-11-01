<h1>Introduzca su api key:</h1>
<h4>Por defecto tendra una api key de pruebas pero debera introducir su api key de google maps si lo va a utilizar para un uso real.</h4>
<form id="formu1" onsubmit="requestGenerica('formu1')" method="post">
    <label for="apikey">Api Key de Google maps:</label><input class="normalInput" onchange="comprobarApi()" type="text" name="apikey" id="apikey" size="60">
    <br>
    <br>
    <input type="hidden" name="action" id="action" value="sendapi">
    <?php submit_button("Siguiente"); ?>
</form>