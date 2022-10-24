<form action="controller/sender.php" method="POST">
<p>Name: <input type="text" name="firstname" /></p>
<p>Nachname: <input type="text" name="lastname" /></p>
<p>Wohnort: <input type="text" name="home" /></p>
<p>Age: <input type="number" name="age" /></p>
<p>Rot<input type="radio" name="color_chose" value="red"> | Blau<input type="radio" name="color_chose" value="blue"> | Gelb<input type="radio" name="color_chose" value="gold"></p>
<p>Sport<input type="checkbox" name="hobby[]" value="Sport"> | Musik<input type="checkbox" name="hobby[]" value="Musik"> | Natur<input type="checkbox" name="hobby[]" value="Natur"></p>
<p><input type="submit" name="submit" value="Senden"> <input type="reset" value="Zurücksetzen"></p>
</form>