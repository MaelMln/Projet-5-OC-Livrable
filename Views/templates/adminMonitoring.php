<h1>Monitoring des Articles</h1>

<table class="admin-monitoring-table">
    <thead>
    <tr>
        <th>
            <a href="?action=adminMonitoring&sort=title&order=<?= $sort === 'title' && $order === 'asc' ? 'desc' : 'asc' ?>">
                Titre <?= $sort === 'title' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
            </a>
        </th>
        <th>
            <a href="?action=adminMonitoring&sort=views&order=<?= $sort === 'views' && $order === 'asc' ? 'desc' : 'asc' ?>">
                Nombre de vues <?= $sort === 'views' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
            </a>
        </th>
        <th>
            <a href="?action=adminMonitoring&sort=comment_count&order=<?= $sort === 'comment_count' && $order === 'asc' ? 'desc' : 'asc' ?>">
                Nombre de commentaires <?= $sort === 'comment_count' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
            </a>
        </th>
        <th>
            <a href="?action=adminMonitoring&sort=date_creation&order=<?= $sort === 'date_creation' && $order === 'asc' ? 'desc' : 'asc' ?>">
                Date de publication <?= $sort === 'date_creation' ? ($order === 'asc' ? '▲' : '▼') : '' ?>
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
