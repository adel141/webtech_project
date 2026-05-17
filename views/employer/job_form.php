<?php $activePage = $activePage ?? 'jobs'; $isEdit = !empty($job); ?>
<h2 class="page-title"><?=$isEdit?'Edit':'Create'?> Job Posting</h2>
<p class="page-sub">Fill in the details for your job posting</p>
<div class="card" style="max-width:800px;">
<form class="col" method="POST" action="<?=BASE_URL?>/employer/jobs/<?=$isEdit?'edit/'.$job['id']:'create'?>"><?=Middleware::csrfField()?>
    <div class="field"><label>Job Title *</label><input type="text" name="title" class="input" value="<?=htmlspecialchars($job['title']??'')?>" required></div>
    <div class="row">
        <div class="field grow"><label>Category</label><select name="category_id" class="select"><option value="">Select</option><?php foreach($categories as $c):?><option value="<?=$c['id']?>" <?=($job['category_id']??'')==$c['id']?'selected':''?>><?=htmlspecialchars($c['name'])?></option><?php endforeach;?></select></div>
        <div class="field grow"><label>Job Type</label><select name="job_type" class="select"><?php foreach(['full-time','part-time','remote','contract'] as $t):?><option value="<?=$t?>" <?=($job['job_type']??'')===$t?'selected':''?>><?=ucfirst($t)?></option><?php endforeach;?></select></div>
    </div>
    <div class="field"><label>Description *</label><textarea name="description" class="textarea" rows="6" required><?=htmlspecialchars($job['description']??'')?></textarea></div>
    <div class="field"><label>Requirements</label><textarea name="requirements" class="textarea" rows="4"><?=htmlspecialchars($job['requirements']??'')?></textarea></div>
    <div class="field"><label>Benefits</label><textarea name="benefits" class="textarea" rows="3"><?=htmlspecialchars($job['benefits']??'')?></textarea></div>
    <div class="row">
        <div class="field grow"><label>Salary Min ($)</label><input type="number" name="salary_min" class="input" step="1000" value="<?=(float)($job['salary_min']??0)?>"></div>
        <div class="field grow"><label>Salary Max ($)</label><input type="number" name="salary_max" class="input" step="1000" value="<?=(float)($job['salary_max']??0)?>"></div>
    </div>
    <div class="row">
        <div class="field grow"><label>Location</label><input type="text" name="location" class="input" value="<?=htmlspecialchars($job['location']??'')?>"></div>
        <div class="field grow"><label>Experience Level</label><select name="experience_level" class="select"><?php foreach(['entry','mid','senior'] as $l):?><option value="<?=$l?>" <?=($job['experience_level']??'')===$l?'selected':''?>><?=ucfirst($l)?></option><?php endforeach;?></select></div>
    </div>
    <div class="row">
        <div class="field grow"><label>Deadline</label><input type="date" name="deadline" class="input" value="<?=htmlspecialchars($job['deadline']??'')?>"></div>
        <div class="field grow"><label>Status</label><select name="status" class="select"><option value="draft" <?=($job['status']??'')==='draft'?'selected':''?>>Draft</option><option value="active" <?=($job['status']??'')==='active'?'selected':''?>>Active</option><option value="closed" <?=($job['status']??'')==='closed'?'selected':''?>>Closed</option></select></div>
    </div>
    <div class="divider"></div>
    <div class="row"><button type="submit" class="btn primary"><i class="fas fa-save"></i> <?=$isEdit?'Update':'Create'?> Job</button><a href="<?=BASE_URL?>/employer/jobs" class="btn ghost">Cancel</a></div>
</form>
</div>
