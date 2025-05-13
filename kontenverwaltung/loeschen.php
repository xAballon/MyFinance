<?php 
include('../header.php');

if(isset($_POST['submit'])){

    $knr = $_POST['knr'];
    $stmt = $pdo->prepare('SELECT kid FROM konten WHERE knr = :knr AND uid = :uid');
    $stmt->execute([':knr' => $knr, ':uid' => $_SESSION['user_id']]);
    $kid = $stmt->fetch();

    $stmt = $pdo->prepare('DELETE FROM transaktionen WHERE quelle = :kid OR ziel = :kid');
    $stmt->execute([':knr' => $knr]);
    $stmt = $pdo->prepare('DELETE FROM konten WHERE kid = :kid');
    $stmt->execute([':knr' => $knr]);
    header('Location: kontenverwaltung.php');
}else{

?>

<form action="<?php echo $_SERVER['SCRIPT_FILENAME']; ?>" method post>
<p>Sind Sie sicher, dass sie dieses Konto und alle damit verbundenen Transaktionen Unwiederuflich löschen wollen? </p>
<input type="hidden" name="knr" value="<?php echo $_GET['knr']; ?> ">
<input type="submit" value="Löschen">
<button href="kontenverwaltung.php">Abbrechen</button>
</form>


<?php 
}
include('../footer.php');
?>