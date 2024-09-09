<h1>Monitoring des Articles</h1>

<table class="admin-monitoring-table">
    <thead>
    <tr>
        <th>
            <a href="?action=admin&sort=title&order=<?= $sort === 'title' && $order === 'asc' ? 'desc' : 'asc' ?>">
                <div class="clickable-cell">Titre</div>
            </a>
        </th>
        <th>
            <a href="?action=admin&sort=views&order=<?= $sort === 'views' && $order === 'asc' ? 'desc' : 'asc' ?>">
                <div class="clickable-cell">Nombre de vues</div>
            </a>
        </th>
        <th>
            <a href="?action=admin&sort=comment_count&order=<?= $sort === 'comment_count' && $order === 'asc' ? 'desc' : 'asc' ?>">
                <div class="clickable-cell">Nombre de commentaires</div>
            </a>
        </th>
        <th>
            <a href="?action=admin&sort=date_creation&order=<?= $sort === 'date_creation' && $order === 'asc' ? 'desc' : 'asc' ?>">
                <div class="clickable-cell">Date de publication</div>
            </a>
        </th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($articles as $index => $article): ?>
        <tr class="<?= $index % 2 == 0 ? 'even-row' : 'odd-row' ?>">
            <td>
                <a href="index.php?action=showArticle&id=<?= $article['id'] ?>">
					<?= htmlspecialchars($article['title']) ?>
                </a>
            </td>
            <td><?= $article['views'] ?></td>
            <td><?= $article['comment_count'] ?></td>
            <td><?= date('d/m/Y', strtotime($article['date_creation'])) ?></td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
