<?php $activePage = $activePage ?? 'profile'; $user = Auth::user(); ?>
<div class="page-header"><h1>Company Profile</h1><p>Manage your company information</p></div>
<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
<div class="card" style="text-align:center;">
    <?php if (!empty($profile['logo_path'])): ?><img src="<?=BASE_URL?>/uploads/logos/<?=htmlspecialchars($profile['logo_path'])?>" style="width:100px;height:100px;border-radius:var(--radius);object-fit:cover;margin-bottom:16px;">
    <?php else: ?><div style="width:100px;height:100px;margin:0 auto 16px;background:var(--accent-glow);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:var(--accent);"><i class="fas fa-building"></i></div><?php endif; ?>
    <form method="POST" action="<?=BASE_URL?>/employer/upload-logo" enctype="multipart/form-data">
        <?=Middleware::csrfField()?>
        <input type="file" name="logo" accept="image/*" class="form-control" style="margin-bottom:8px;"><button type="submit" class="btn btn-secondary btn-sm btn-block">Update Logo</button>
    </form>
</div>
<div class="card">
    <form method="POST" action="<?=BASE_URL?>/employer/profile"><?=Middleware::csrfField()?>
        <div class="form-group"><label>Company Name</label><input type="text" name="company_name" class="form-control" value="<?=htmlspecialchars($profile['company_name']??'')?>"></div>
        <div class="form-row">
            <div class="form-group"><label>Industry</label><input type="text" name="industry" class="form-control" value="<?=htmlspecialchars($profile['industry']??'')?>"></div>
            <div class="form-group"><label>Company Size</label><select name="company_size" class="form-control"><?php foreach(['1-10','11-50','51-200','201-500','500+'] as $s):?><option <?=($profile['company_size']??'')===$s?'selected':''?>><?=$s?></option><?php endforeach;?></select></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="4"><?=htmlspecialchars($profile['description']??'')?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label>Website</label><input type="url" name="website" class="form-control" value="<?=htmlspecialchars($profile['website']??'')?>"></div>
            <div class="form-group"><label>Address</label><input type="text" name="address" class="form-control" value="<?=htmlspecialchars($profile['address']??'')?>"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Profile</button>
    </form>
</div>
</div>
