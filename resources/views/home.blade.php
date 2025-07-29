<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jadwal Pelajaran</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .day-card {
      border-left: 5px solid #0d6efd;
    }
    .schedule-item {
      margin-bottom: 10px;
    }
    .todo-item {
      border: 1px dashed #ccc;
      padding: 8px;
      margin-top: 5px;
    }
    .greeting-banner {
      background: linear-gradient(to right, #0d6efd, #6ea8fe);
      color: white;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .menu-links a {
      margin-right: 10px;
    }
  </style>
</head>
<body>
<div class="container my-4">
  <div class="greeting-banner text-center">
    <h4 id="greeting-text">Selamat datang!</h4>
    <p id="current-time">Waktu sekarang: --:--</p>
  </div>

  <div class="menu-links mb-4">
    <a href="#" id="btnToday" class="btn btn-primary" onclick="showTodaySchedule()">Hari Ini</a>
    <a href="#" id="btnAll" class="btn btn-outline-primary" onclick="showAllSchedule()">Jadwal Lengkap</a>
  </div>  

  <div id="nearest-schedule" class="mb-4"></div>
  <div id="schedule-container"></div>

  <hr>

</div>

<script>
const scheduleData = {
  "Senin": [
    { jam: "1, 2, 3", mapel: "Geografi", guru: "Darmayetti, S.Pd." },
    { jam: "4, 5", mapel: "Seni Budaya", guru: "Dra. Zofrilda" },
    { jam: "6, 7", mapel: "Kewirausahaan", guru: "Laridho Syahmitra, S.Pd., Gr" },
    { jam: "8, 9, 10", mapel: "Biologi", guru: "Lili Fitriyanti, S.Pd." }
  ],
  "Selasa": [
    { jam: "1, 2, 3", mapel: "Sejarah", guru: "Silvani, S.Pd." },
    { jam: "4", mapel: "Bahasa Indonesia", guru: "Esya Yunisra Asiz, S.Pd." },
    { jam: "5, 6, 7", mapel: "Sosiologi", guru: "Muhammad Harz Ardyani" },
    { jam: "8, 9, 10", mapel: "Fisika", guru: "Dra. Titi Utami" }
  ],
  "Rabu": [
    { jam: "1, 2, 3", mapel: "Kimia", guru: "Verawati, S.Pd." },
    { jam: "4", mapel: "BK", guru: "Pradefitani, S.Pd" },
    { jam: "5, 6, 7", mapel: "PJOK", guru: "Irfan Saputra, S.Pd." },
    { jam: "8, 9, 10", mapel: "PAI", guru: "Ratna Wulis, M.Pd.I" }
  ],
  "Kamis": [
    { jam: "1, 2", mapel: "TIK", guru: "Resmawati, S.Pd" },
    { jam: "3, 4", mapel: "PPKn", guru: "Irfan Saputra, S.Pd." },
    { jam: "5, 6, 7", mapel: "Bahasa Indonesia", guru: "Esya Yunisra Asiz, S.Pd." },
    { jam: "8, 9, 10", mapel: "Ekonomi", guru: "Yumairera, SE" }
  ],
  "Jumat": [
    { jam: "1, 2, 3", mapel: "Bahasa Inggris", guru: "Desi Rafiani, S.Pd., Gr./GTT" },
    { jam: "4, 5, 6, 7", mapel: "MTK", guru: "Yuni Hardianty Hamzon, S.Pd." }
  ]
};

const timeSlot = {
  "Senin": {
    1: "07.45 - 08.30", 2: "08.30 - 09.15", 3: "09.15 - 10.00",
    4: "10.20 - 11.05", 5: "11.05 - 11.45", 6: "11.45 - 12.30",
    7: "13.10 - 13.55", 8: "13.55 - 14.40", 9: "14.40 - 15.25", 10: "15.25 - 16.10"
  },
  "Selasa": {
    1: "07.10 - 07.55", 2: "07.55 - 08.40", 3: "08.40 - 09.25",
    4: "09.25 - 10.10", 5: "10.30 - 11.15", 6: "11.15 - 12.00",
    7: "12.00 - 12.45", 8: "13.30 - 14.15", 9: "14.15 - 15.00", 10: "15.00 - 15.45"
  },
  "Rabu": {
    1: "07.10 - 07.55", 2: "07.55 - 08.40", 3: "08.40 - 09.25",
    4: "09.25 - 10.10", 5: "10.30 - 11.15", 6: "11.15 - 12.00",
    7: "12.00 - 12.45", 8: "13.30 - 14.15", 9: "14.15 - 15.00", 10: "15.00 - 15.45"
  },
  "Kamis": {
    1: "06.55 - 07.55", 2: "07.55 - 08.40", 3: "08.40 - 09.25",
    4: "09.25 - 10.10", 5: "10.30 - 11.15", 6: "11.15 - 12.00",
    7: "12.00 - 12.45", 8: "13.30 - 14.15", 9: "14.15 - 15.00", 10: "15.00 - 15.45"
  },
  "Jumat": {
    1: "07.10 - 07.55", 2: "07.55 - 08.40", 3: "08.40 - 09.25",
    4: "09.25 - 10.10", 5: "10.30 - 11.15", 6: "11.15 - 12.00",
    7: "12.00 - 12.45", 8: "13.30 - 14.15", 9: "14.15 - 15.00", 10: "15.00 - 15.45"
  }
};

const dayMap = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

function updateTime() {
  const now = new Date();
  const hour = now.getHours();
  const minute = now.getMinutes().toString().padStart(2, '0');
  document.getElementById('current-time').innerText = `Waktu sekarang: ${hour}:${minute}`;
  document.getElementById('greeting-text').innerText =
    hour < 10 ? "Selamat pagi!" :
    hour < 15 ? "Selamat siang!" :
    hour < 18 ? "Selamat sore!" : "Selamat malam!";
}

function getTime(jamStr, day) {
  const jamArray = jamStr.split(',').map(j => parseInt(j.trim())).sort((a, b) => a - b);
  let result = [];
  let start = jamArray[0], end = jamArray[0];
  for (let i = 1; i <= jamArray.length; i++) {
    if (jamArray[i] === end + 1) {
      end = jamArray[i];
    } else {
        const startTime = timeSlot[day]?.[start]?.split(' - ')[0] || "-";
        const endTime = timeSlot[day]?.[end]?.split(' - ')[1] || "-";

        result.push(start === end
        ? `Jam ${start} (${timeSlot[day]?.[start] || "-"})`
        : `Jam ${start}-${end} (${startTime} - ${endTime})`);

      start = jamArray[i];
      end = jamArray[i];
    }
  }
  return result.join(', ');
}

function renderSchedule(day) {
  const lessons = scheduleData[day];
  const card = document.createElement('div');
  card.className = 'card mb-3 day-card';
  const listItems = [];

  lessons.forEach((lesson, index) => {
    listItems.push(`
      <li class="list-group-item schedule-item">
        <strong>Jam:</strong> ${getTime(lesson.jam, day)}<br>
        <strong>Mata Pelajaran:</strong> ${lesson.mapel}<br>
        <strong>Guru:</strong> ${lesson.guru}
      </li>
    `);

    // Tambah istirahat statis
    if (day === "Senin") {
      if (index === 0) listItems.push(`<li class="list-group-item bg-light text-center"><em>Istirahat (10.00 - 10.20)</em></li>`);
      if (index === 1) listItems.push(`<li class="list-group-item bg-light text-center"><em>Istirahat (12.30 - 13.10)</em></li>`);
    } else if (["Selasa", "Rabu", "Kamis"].includes(day)) {
      if (index === 0) listItems.push(`<li class="list-group-item bg-light text-center"><em>Istirahat (10.10 - 10.30)</em></li>`);
      if (index === 2) listItems.push(`<li class="list-group-item bg-light text-center"><em>Istirahat (12.45 - 13.30)</em></li>`);
    } else if (day === "Jumat") {
      if (index === 0) listItems.push(`<li class="list-group-item bg-light text-center"><em>Istirahat (10.00 - 10.20)</em></li>`);
    }
  });

  card.innerHTML = `
    <div class="card-body">
      <h5 class="card-title text-primary">${day}</h5>
      <ul class="list-group list-group-flush">
        ${listItems.join('')}
      </ul>
    </div>
  `;
  document.getElementById('schedule-container').appendChild(card);
}


function showTodaySchedule() {
    document.getElementById("btnToday").classList.add("btn-primary");
    document.getElementById("btnToday").classList.remove("btn-outline-primary");
    document.getElementById("btnAll").classList.add("btn-outline-primary");
    document.getElementById("btnAll").classList.remove("btn-primary");
    const today = dayMap[new Date().getDay()];
    document.getElementById('schedule-container').innerHTML = '';
    renderSchedule(today);

    
}

function showAllSchedule() {
    document.getElementById("btnAll").classList.add("btn-primary");
    document.getElementById("btnAll").classList.remove("btn-outline-primary");
    document.getElementById("btnToday").classList.add("btn-outline-primary");
    document.getElementById("btnToday").classList.remove("btn-primary");
    document.getElementById('schedule-container').innerHTML = '';
    for (const day in scheduleData) {
        renderSchedule(day);
    }
}

function findNearestSchedule() {
  const today = dayMap[new Date().getDay()];
  const now = new Date();
  const currentMinutes = now.getHours() * 60 + now.getMinutes();

  const lessons = scheduleData[today];
  if (!lessons) return;

  for (const lesson of lessons) {
    const jamArray = lesson.jam.split(',').map(j => parseInt(j.trim()));
    for (const jam of jamArray) {
      const timeRange = timeSlot[today]?.[jam];
      if (timeRange) {
        const [startHour, startMinute] = timeRange.split(' - ')[0].split('.').map(Number);
        const startInMinutes = startHour * 60 + startMinute;
        if (startInMinutes > currentMinutes) {
          document.getElementById('nearest-schedule').innerHTML = `
            <div class="alert alert-info">
              <strong>Jadwal Terdekat:</strong><br>
              ${lesson.mapel} (${getTime(lesson.jam, today)}) - ${lesson.guru}
            </div>
          `;
          return;
        }
      }
    }
  }

  // Jika semua pelajaran hari ini sudah lewat
  document.getElementById('nearest-schedule').innerHTML = `
    <div class="alert alert-secondary">
      Tidak ada jadwal pelajaran berikutnya untuk hari ini.
    </div>
  `;
}




updateTime();
showTodaySchedule();
findNearestSchedule();
setInterval(updateTime, 60000);
</script>

  
</body>
</html>
