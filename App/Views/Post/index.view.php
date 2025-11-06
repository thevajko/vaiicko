<?php

/** @var Framework\Support\LinkGenerator $link */
/** @var \Framework\Core\IAuthenticator $auth */
/** @var array $formErrors */

/** @var \App\Models\Post[] $posts */

use App\Configuration;

?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <a href="<?= $link->url('post.add') ?>" class="btn btn-success">Pridať príspevok</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <?php foreach ($posts as $post): ?>
            <div class="col-3 d-flex gap-4 flex-column">
                <div class="border post d-flex flex-column">
                    <div>
                        <img src="<?= $link->asset(Configuration::UPLOAD_URL . $post->getPicture()) ?>" class="img-fluid" alt="Post image">
                    </div>
                    <div class="m-2">
                        <?= $post->getText() ?>
                    </div>
                    <div class="m-2">
                        Autor: <strong><?= $post->getAuthor() ?></strong>
                    </div>
                    <div class="m-2 d-flex gap-2 justify-content-end">
                        <?php if ($auth->isLogged() && ($auth->user?->getName() == $post->getAuthor())): ?>
                            <a href="<?= $link->url('post.edit', ['id' => $post->getId()]) ?>" class="btn btn-primary">Upraviť</a>
                            <a href="<?= $link->url('post.delete', ['id' => $post->getId()]) ?>" class="btn btn-danger">Zmazať</a>
                        <?php else: ?>
                            <a href="<?= $link->url('like.toggle', ['id' => $post->getId()]) ?>" class="btn btn-primary btn-sm"><?= $post->getLikeCount() ?> <i
                                        class="bi bi-hand-thumbs-up"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>