<?php if (Auth::check()): ?>
        </div><!-- .page-content -->
    </div><!-- .main-content -->
</div><!-- .app-wrapper -->
<?php endif; ?>
<script src="<?= BASE_URL ?>/js/app.js"></script>
<?php if (isset($scripts)): ?>
    <?= $scripts ?>
<?php endif; ?>
</body>
</html>
