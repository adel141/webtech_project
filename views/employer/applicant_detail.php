<?php $activePage = $activePage ?? 'jobs'; ?>
<div class="page-header"><a href="javascript:history.back()" class="btn btn-secondary btn-sm" style="margin-bottom:8px;"><i class="fas fa-arrow-left"></i> Back</a><h1>Applicant Detail</h1></div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
<div class="card">
    <h3 style="margin-bottom:16px;">Candidate Profile</h3>
    <p><strong>Name:</strong> <?=htmlspecialchars($app['seeker_name'])?></p>
    <p><strong>Email:</strong> <?=htmlspecialchars($app['seeker_email'])?></p>
    <p><strong>Headline:</strong> <?=htmlspecialchars($app['headline']??'-')?></p>
    <p><strong>Skills:</strong> <?=htmlspecialchars($app['skills']??'-')?></p>
    <p><strong>Experience:</strong> <?=($app['years_experience']??0)?> years</p>
    <p><strong>Education:</strong> <?=htmlspecialchars($app['education_level']??'-')?></p>
    <p><strong>Expected Salary:</strong> $<?=number_format($app['expected_salary']??0)?></p>
    <p><strong>Location:</strong> <?=htmlspecialchars($app['preferred_location']??'-')?></p>
    <?php if(!empty($app['resume_path'])):?><a href="<?=PUBLIC_URL?>/uploads/resumes/<?=htmlspecialchars($app['resume_path'])?>" target="_blank" class="btn btn-primary btn-sm" style="margin-top:12px;"><i class="fas fa-download"></i> Download Resume</a><?php endif;?>
</div>
<div>
    <div class="card" style="margin-bottom:20px;">
        <h3 style="margin-bottom:12px;">Application</h3>
        <p><strong>Job:</strong> <?=htmlspecialchars($app['title'])?></p>
        <p><strong>Status:</strong> <span class="badge badge-info"><?=ucfirst($app['status'])?></span></p>
        <p><strong>Applied:</strong> <?=date('M d, Y',strtotime($app['applied_at']))?></p>
        <h4 style="margin:16px 0 8px;">Cover Letter</h4>
        <div style="color:var(--text-secondary);white-space:pre-line;background:var(--bg-glass);padding:16px;border-radius:var(--radius-sm);"><?=nl2br(htmlspecialchars($app['cover_letter']??'No cover letter provided.'))?></div>
    </div>
    <div class="card">
        <h3 style="margin-bottom:12px;">Send Message</h3>
        <form method="POST" action="<?=BASE_URL?>/employer/messages/send"><?=Middleware::csrfField()?>
            <input type="hidden" name="recipient_id" value="<?=$app['seeker_id']?>">
            <input type="hidden" name="application_id" value="<?=$app['id']?>">
            <div class="form-group"><textarea name="body" class="form-control" rows="4" placeholder="e.g. Interview invitation..." required></textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send</button>
        </form>
    </div>
</div>
</div>
