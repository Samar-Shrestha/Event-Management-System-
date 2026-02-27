<?php
include("header.php");
include("Database/connect.php");

$id = (int)$_GET['id'];

if (isset($_POST['submit'])) {

    $list = mysqli_query($con, "SELECT * FROM wedding WHERE id = $id");
    $q = mysqli_fetch_assoc($list);

    if ($q) {
        $pid   = $q['id'];
        $img   = $q['img'];   // ✔ correct
        $nm    = $q['nm'];    // ✔ correct
        $price = $q['price'];

        mysqli_query($con, "TRUNCATE TABLE temp");      // clears cart
        mysqli_query($con, "DELETE FROM booking");      // fixed SQL

        $qr1 = mysqli_query(
            $con,
            "INSERT INTO temp (id, img, nm, price)
             VALUES ('$pid', '$img', '$nm', '$price')"
        );

        if ($qr1) {
            echo "<script>window.location='cart.php';</script>";
        } else {
            echo "<script>alert('Not added to cart');</script>";
        }
    }
}
?>

<?php
$list = mysqli_query($con, "SELECT * FROM wedding WHERE id = $id");
$q = mysqli_fetch_assoc($list);
?>

<div role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <a href="gallery.php">BACK TO WEDDING</a>
            </div>

            <form method="post">
                <div class="modal-body">
                    <img src="images/<?php echo $q['img']; ?>" height="300" width="545">

                    <p>
                        <br>Name : <?php echo $q['nm']; ?><br>
                        Price : <?php echo $q['price']; ?><br>

                        <input type="submit" name="submit" value="BOOK NOW" class="btn my">
                    </p>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include("footer.php"); ?>


