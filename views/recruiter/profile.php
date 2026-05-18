<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recruiter | Profile</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="../../public/js/recruiter.js"></script>
</head>
<body>
    <div class="card" style="max-width:720px">
        <form onsubmit="saveRecruiterProfile()">
            <div class="field" style="margin-bottom:10px"><label>Agency name</label><input class="input" id="agency_name"></div>
            <div class="field" style="margin-bottom:10px"><label>Specialization</label><input class="input" id="specialization"></div>
            <div class="field" style="margin-bottom:10px"><label>Description</label><textarea class="textarea" id="description"></textarea></div>
            <div class="field" style="margin-bottom:10px"><label>Website</label><input class="input" id="website"></div>
            <button class="btn accent">Save profile</button>
        </form>
    </div>
</body>
<script>loadRecruiterProfile();</script>
</html>
