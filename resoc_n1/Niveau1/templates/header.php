<header>
    <a href='admin.php'><img class="logo" src="./oeuf_dore.jpg" alt="Logo de notre réseau social" /></a>

    <nav id="menu">
        <a href="news.php">Actualités</a>
        <a href="wall.php?user_id=<?php echo $currentId ?>">Mur</a>
        <a href="feed.php?user_id=<?php echo $currentId ?>">Flux</a>
        <a href="tags.php?tag_id=1">Mots-clés</a>
    </nav>
    <nav id="user">
        <a>Profil</a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $currentId ?>">Paramètres</a></li>
            <li><a href="followers.php?user_id=<?php echo $currentId ?>">Mes abonné.e.s</a></li>
            <li><a href="subscriptions.php?user_id=<?php echo $currentId ?>">Mes abonnements</a></li>
            <li><a href="./scripts/deconnexion.php?user_id=<?php echo $currentId ?>">Deconnexion</a></li>
        </ul>

    </nav>
</header>