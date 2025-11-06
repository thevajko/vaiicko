<?php

/** @var Framework\Support\LinkGenerator $link */
/** @var array $formErrors */
/** @var \App\Models\Post $post */
?>

<?php if (!is_null(@$formErrors)): ?>
    <?php foreach ($formErrors as $error): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-10 d-flex gap-4 flex-column">
            <form method="post" action="<?= $link->url('post.save') ?>" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= @$post?->getId() ?>">

                <label for="picture" class="form-label fw-bold">Súbor obrázka</label>
                <div class="input-group mb-3 has-validation">
                    <input type="file" class="form-control " name="picture" id="picture">
                </div>
                <?php if (@$post?->getPicture() != ""): ?>
                    <div class="text-muted mb-3">Pôvodný súbor: <?= substr($post->getPicture(), strpos($post->getPicture(), '-') + 1) ?></div>
                <?php endif; ?>
                <label for="text" class="form-label fw-bold">Text príspevku</label>
                <div class="input-group has-validation mb-3 ">
                    <textarea class="form-control" aria-label="With textarea" name="text" id="text"><?= @$post?->getText() ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Uložiť</button>
            </form>
        </div>
    </div>
</div>