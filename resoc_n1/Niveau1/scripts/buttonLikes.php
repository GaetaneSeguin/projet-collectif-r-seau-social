<form class="form-like" action="scripts/like.php?user_id=<?php echo $currentId ?>" method="post">
    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
    <?php
    $checkLike = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
    $checkLikeStmt = $mysqli->prepare($checkLike);
    $checkLikeStmt->bind_param("ii", $currentId, $post['id']);
    $checkLikeStmt->execute();
    $checkLikeResult = $checkLikeStmt->get_result();
    $likeClass = ($checkLikeResult->num_rows === 1) ? 'liked' : '';    ?> <button class="btn-like" type="submit" name="like"><svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#121212
">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path class="like <?php echo $likeClass; ?>" d="M2 9.1371C2 14 6.01943 16.5914 8.96173 18.9109C10 19.7294 11 20.5 12 20.5C13 20.5 14 19.7294 15.0383 18.9109C17.9806 16.5914 22 14 22 9.1371C22 4.27416 16.4998 0.825464 12 5.50063C7.50016 0.825464 2 4.27416 2 9.1371Z" fill="r"></path>
            </g>
        </svg> <?php
                $likeNumber = "SELECT COUNT(*) as like_number FROM likes WHERE post_id = " . $post['id'];
                $likeNumber = $mysqli->query($likeNumber);
                $likeNumber = $likeNumber->fetch_assoc();
                echo $likeNumber['like_number'];
                ?> </button>

</form>