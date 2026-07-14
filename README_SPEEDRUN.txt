TALENTROUTE FINAL SPEEDRUN

1. Backup folder lama dan database.
2. Letak folder talentroute ini dalam C:\xampp\htdocs\
3. phpMyAdmin > database talentroute > SQL > run UPDATE_DATABASE_FIRST.sql
4. Pastikan config/app_local.php database=talentroute, username=root, password kosong.
5. Buka http://localhost/talentroute

Akaun asal database:
Admin: admin@talentroute.com / admin123456
Student: student@talentroute.com / password123
Company: hr@nexustech.com / password123

Test flow:
- Student upload resume, cari internship, apply, mohon cuti.
- Company lihat Applications dan update status/date/remarks.
- Admin lihat dashboard, student/company list, applications, approve/reject cuti.
- Student cetak surat selepas status Approved/Offered.
