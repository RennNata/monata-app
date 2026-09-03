<template>
  <div class="app-layout">
    <!-- Sidebar Kiri -->
    <aside class="sidebar">
      <div class="sidebar__brand">
        <span class="dot blue"></span>
        <span class="dot dark"></span>
        <span class="brand-text">Monata</span>
      </div>
      
      <nav class="sidebar__menu">
        <a href="#" class="menu-item active">📊 Overview</a>
        <a href="#" class="menu-item">📝 My Tasks</a>
        <a href="#" class="menu-item">⏰ Time Tracker</a>
        <a href="#" class="menu-item">📁 Surat Masuk</a>
        <a href="#" class="menu-item">📈 Reports</a>
        <a href="#" class="menu-item">⚙️ Settings</a>
      </nav>

      <div class="sidebar__footer">
        <router-link to="/" class="btn-logout">⬅ Keluar Website</router-link>
      </div>
    </aside>

    <!-- Area Utama Kanan -->
    <main class="main-content">
      <!-- Frame Biru Luar -->
      <div class="blue-frame">
        <!-- White Board / Inner Container -->
        <div class="white-board">
          
          <!-- Topbar dalam Dashboard -->
          <header class="dashboard-topbar">
            <div class="topbar-left">
              <span class="icon-box">::</span>
              <span class="date-badge">Monday, September 3</span>
            </div>
            <div class="search-box">
              <input type="text" placeholder="Search tasks, mail, or reports..." />
            </div>
            <div class="topbar-right">
              <span class="notif-badge">🔔</span>
              <div class="user-profile-mini">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=TimMonata" alt="Avatar" />
                <span>Tim MONATA</span>
              </div>
            </div>
          </header>

          <!-- Judul Sapaan -->
          <h1 class="welcome-title">Good morning, Tim MONATA</h1>

          <!-- Grid Konten Dashboard ala Dribbble -->
          <div class="dashboard-grid">
            
            <!-- Baris 1: To-Do List, Time Tracker, Activity -->
            <div class="card todo-card">
              <div class="card-title">
                <span>✏️ To do list</span>
                <span class="badge-count">{{ tasks.length }}</span>
              </div>
              <ul class="task-list">
                <li v-for="task in tasks" :key="task.id" class="task-item">
                  <input type="checkbox" v-model="task.done" />
                  <span :class="{ 'done': task.done }">{{ task.title }}</span>
                </li>
              </ul>
              <button @click="addTask" class="btn-action">+ Add new task</button>
            </div>

            <div class="card time-card">
              <div class="card-title"><span>⏱️ Time tracker</span></div>
              <div class="timer-display">04:21:58</div>
              <div class="timer-actions">
                <button class="btn-ico">⏸</button>
                <button class="btn-ico red">⏹</button>
              </div>
            </div>

            <div class="card activity-card">
              <div class="card-title"><span>📈 Activity</span></div>
              <div class="progress-circle">
                <div class="inner-val">75%</div>
              </div>
              <p class="activity-text">8/12 tasks completed today</p>
            </div>

            <!-- Baris 2: Task Assigned & Quick Stats -->
            <div class="card wide-card">
              <div class="card-title"><span>📋 Tasks I've assigned</span></div>
              <div class="sub-task-list">
                <div class="sub-task-row">
                  <span>Rekap Surat Masuk Dinas</span>
                  <span class="status-pill blue">In Progress (60%)</span>
                </div>
                <div class="sub-task-row">
                  <span>Arsip Laporan Keuangan Bulanan</span>
                  <span class="status-pill green">Completed (100%)</span>
                </div>
                <div class="sub-task-row">
                  <span>Input Data Siswa Baru PPDB</span>
                  <span class="status-pill orange">Pending (30%)</span>
                </div>
              </div>
            </div>

            <div class="card reminder-small-card">
              <div class="card-title"><span>📌 Reminder</span></div>
              <p class="reminder-text">Jangan lupa update laporan bulanan sebelum tanggal 5!</p>
              <span class="time-tag">07:00 - 15:00</span>
            </div>

          </div>

        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const tasks = ref([
  { id: 1, title: 'Cek disposisi surat masuk dari kecamatan', done: true },
  { id: 2, title: 'Koordinasi jadwal rapat guru dengan Kepala Sekolah', done: false },
  { id: 3, title: 'Rekap absensi staf tata usaha minggu ini', done: false }
])

const addTask = () => {
  const id = Date.now()
  tasks.value.push({
    id,
    title: `Tugas Baru Tata Usaha #${tasks.value.length + 1}`,
    done: false
  })
}
</script>

<style scoped>
/* Layout Keseluruhan (Sidebar + Main) */
.app-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8fafc;
  font-family: 'Inter', sans-serif;
}

/* Sidebar Kiri */
.sidebar {
  width: 260px;
  background: #ffffff;
  border-right: 1px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  padding: 24px;
  position: fixed;
  height: 100vh;
  z-index: 10;
}
.sidebar__brand {
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 700;
  font-size: 20px;
  margin-bottom: 36px;
}
.dot { width: 8px; height: 8px; border-radius: 50%; }
.dot.blue { background: #3b82f6; }
.dot.dark { background: #0f172a; }

.sidebar__menu {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex: 1;
}
.menu-item {
  text-decoration: none;
  color: #64748b;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  transition: 0.2s;
}
.menu-item:hover, .menu-item.active {
  background: #eff6ff;
  color: #3b82f6;
}
.sidebar__footer {
  border-top: 1px solid #f1f5f9;
  padding-top: 16px;
}
.btn-logout {
  color: #ef4444;
  text-decoration: none;
  font-size: 13px;
  font-weight: 600;
}

/* Area Main Content (Menjorok ke kanan karena sidebar fixed) */
.main-content {
  flex: 1;
  margin-left: 260px;
  padding: 24px;
  background: #f1f5f9;
  min-height: 100vh;
}

/* Frame Biru Luar */
.blue-frame {
  background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
  border-radius: 28px;
  padding: 24px;
  box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.15);
}

/* White Board / Kontainer Putih */
.white-board {
  background: #ffffff;
  border-radius: 20px;
  padding: 32px;
}

/* Topbar Internal di dalam Board */
.dashboard-topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding-bottom: 16px;
  border-bottom: 1px solid #f1f5f9;
}
.topbar-left {
  display: flex;
  align-items: center;
  gap: 12px;
}
.icon-box {
  background: #f8fafc;
  padding: 6px 10px;
  border-radius: 6px;
  font-weight: bold;
  border: 1px solid #e2e8f0;
}
.date-badge {
  font-size: 13px;
  color: #64748b;
  font-weight: 500;
}
.search-box input {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 8px 16px;
  border-radius: 8px;
  width: 280px;
  font-size: 13px;
  outline: none;
}
.topbar-right {
  display: flex;
  align-items: center;
  gap: 16px;
}
.notif-badge {
  cursor: pointer;
  font-size: 16px;
}
.user-profile-mini {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  color: #1e293b;
}
.user-profile-mini img {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #cbd5e1;
}

/* Judul Sapaan */
.welcome-title {
  font-size: 24px;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 24px;
}

/* Grid Layout Dashboard */
.dashboard-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr 1fr;
  gap: 20px;
}

/* Kartu Card */
.card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.card-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
  font-weight: 600;
  color: #334155;
  margin-bottom: 16px;
}
.badge-count {
  background: #f1f5f9;
  padding: 2px 8px;
  border-radius: 10px;
  font-size: 12px;
  color: #64748b;
}

/* Todo List Styling */
.task-list {
  list-style: none;
  padding: 0;
  margin: 0 0 16px 0;
}
.task-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: #1e293b;
  margin-bottom: 10px;
}
.task-item input { accent-color: #3b82f6; width: 16px; height: 16px; cursor: pointer; }
.task-item span.done { text-decoration: line-through; color: #94a3b8; }
.btn-action {
  background: #eff6ff;
  color: #3b82f6;
  border: none;
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  font-weight: 600;
  font-size: 13px;
  cursor: pointer;
}
.btn-action:hover { background: #dbeafe; }

/* Time Tracker */
.timer-display {
  font-size: 32px;
  font-weight: 700;
  color: #0f172a;
  text-align: center;
  margin: 20px 0;
}
.timer-actions {
  display: flex;
  justify-content: center;
  gap: 10px;
}
.btn-ico {
  background: #f1f5f9;
  border: none;
  padding: 8px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
}
.btn-ico.red { color: #ef4444; }

/* Activity Card */
.progress-circle {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: conic-gradient(#3b82f6 75%, #e2e8f0 75%);
  margin: 10px auto;
  display: flex;
  align-items: center;
  justify-content: center;
}
.inner-val {
  width: 76px;
  height: 76px;
  background: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 18px;
  color: #0f172a;
}
.activity-text {
  text-align: center;
  font-size: 12px;
  color: #64748b;
  margin-top: 8px;
}

/* Wide Card (Baris 2) */
.wide-card {
  grid-column: span 2;
}
.sub-task-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.sub-task-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
  color: #334155;
  padding-bottom: 8px;
  border-bottom: 1px solid #f8fafc;
}
.status-pill {
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 6px;
  font-weight: 600;
}
.status-pill.blue { background: #eff6ff; color: #3b82f6; }
.status-pill.green { background: #f0fdf4; color: #16a34a; }
.status-pill.orange { background: #fff7ed; color: #ea580c; }

/* Reminder Small Card */
.reminder-text {
  font-size: 13px;
  color: #475569;
  line-height: 1.4;
  margin-bottom: 12px;
}
.time-tag {
  background: #eff6ff;
  color: #3b82f6;
  font-size: 11px;
  padding: 4px 8px;
  border-radius: 6px;
  font-weight: 600;
}

/* Responsive */
@media(max-width: 1024px) {
  .dashboard-grid { grid-template-columns: 1fr; }
  .wide-card { grid-column: span 1; }
  .sidebar { display: none; }
  .main-content { margin-left: 0; }
}
</style>