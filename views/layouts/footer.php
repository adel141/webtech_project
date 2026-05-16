<?php if (Auth::check()): ?>
        </div><!-- .jp-content -->
    </div><!-- .jp-main -->
<?php endif; ?>
<script src="<?= BASE_URL ?>/js/app.js"></script>
<?php if (isset($scripts)): ?>
    <?= $scripts ?>
<?php endif; ?>
</body>
</html>
