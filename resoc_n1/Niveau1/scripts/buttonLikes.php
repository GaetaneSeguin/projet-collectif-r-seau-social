<form action="scripts/like.php?user_id=<?php echo $currentId ?>" method="post">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <button type="submit" name="like">♥ <?php
                                        $likeNumber = "SELECT COUNT(*) as like_number FROM likes WHERE post_id = " . $post['id'];
                                        $likeNumber = $mysqli->query($likeNumber);
                                        $likeNumber = $likeNumber->fetch_assoc();
                                        echo $likeNumber['like_number'];
                                        ?> </button>
</form>